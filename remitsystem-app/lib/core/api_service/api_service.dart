import 'dart:async';
import 'dart:developer';
import 'dart:io';

import 'package:dio/dio.dart';
import 'package:dio/io.dart';
import 'package:dio_intercept_to_curl/dio_intercept_to_curl.dart';
import 'package:flutter/foundation.dart';

import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/resource/app_string_const.dart';
import 'package:remit_management/core/utils/local_db.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/sign_in/bloc/global_state.dart';

import '../utils/utils.dart';

class ApiService {
  final dio = createDio();
  String _token = "";
  // String _apiKey = UrlConsts.apiKey;

  ApiService._internal();

  static final _singleton = ApiService._internal();
  final StreamController<bool> _logoutController = StreamController<bool>.broadcast();

  Stream<bool> get logoutStream => _logoutController.stream;

  factory ApiService() => _singleton;

  static Dio createDio() {
    var dio = Dio(BaseOptions(
      baseUrl: UrlConsts.baseUrl, //For example : https:www.example.com
      connectTimeout: const Duration(seconds: 60),
      //30 secs
      receiveTimeout: const Duration(seconds: 60),
      //30 secs
      sendTimeout: const Duration(seconds: 60),
      //20secs
      validateStatus: (status) {
        return status != null && status < 500; // Accept all status codes less than 500
      },
    ));
    dio.interceptors.clear();
    dio.interceptors.addAll({ErrorInterceptor(dio)});
    dio.interceptors.add(HeaderInterceptor());
    dio.interceptors.add(DioInterceptToCurl(
      printOnSuccess: true,
    ));

    dio.interceptors.addAll({
      LogInterceptor(
          requestBody: true,
          responseBody: true,
          error: true,
          logPrint: (v) {
            log(v.toString());
          })
    });
    return dio;
  }

  // String get token => _token;

  // set token(String? value) {
  //   if (value != null && value.isNotEmpty) {
  //     _token = value;
  //   }
  // }

  // String get apiKey => _apiKey;

  // set apiKey(String? value) {
  //   if (value != null && value.isNotEmpty) {
  //     _apiKey = value;
  //   }
  // }

  void clearToken() {
    _token = "";
  }

  void clearApiKey() {
    _clearTokens();
  }

  // Future<bool> _refreshToken() async {
  //   String? refreshToken = await _getRefreshToken();

  //   if (refreshToken == null) {
  //     return false;
  //   }

  //   try {
  //     Response response = await dio.post("${UrlConsts.baseUrl}/refresh", data: {
  //       "refresh_token": refreshToken,
  //     });

  //     if (response.statusCode == 200) {
  //       await _saveTokens(
  //           response.data["access_token"], response.data["refresh_token"]);
  //       return true;
  //     }
  //   } catch (_) {
  //     // throw Exception(e.toString());
  //   }

  //   return false;
  // }

  void forceLogout() async {
    await _clearTokens();
    _logoutController.add(true); // Notify Bloc to log out
  }

  Future<void> saveTokens(String accessToken, String refreshToken) async {
    LocalDb.saveData(key: 'api_token', value: accessToken);
    LocalDb.saveData(key: 'refresh_token', value: refreshToken);
  }

  Future<void> _clearTokens() async {
    LocalDb.deleteData(key: 'api_token');
    LocalDb.deleteData(key: 'refresh_token');
  }

  Future<String?> _getAccessToken() async {
    final token = GlobalState.instance.token ?? LocalDb.getData<String?>(key: AppStringConst.apiToken);
    debugPrint("Access token: $token");
    return token;
  }

  // Future<String?> _getRefreshToken() async {
  //   return LocalDb.getData(key: 'refresh_token');
  // }

  ///[GET] We will use this method in order to process get requests
  Future<ResModel> get(
    String path, {
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
    String? baseUrl,
    void Function(int, int)? onReceiveProgress,
    bool addRequestInterceptor = false,
    bool addAuthInterceptor = false,
  }) async {
    await _addRequestAndAuthInterceptor(
        // addRequestInterceptor: addRequestInterceptor,
        addAuthInterceptor: addAuthInterceptor);
    // debugPrint("QUERY PARAMS=>${queryParameters}");
    try {
      var response = await dio.get<Map<String, dynamic>?>((baseUrl ?? dio.options.baseUrl) + path,
          onReceiveProgress: onReceiveProgress, cancelToken: cancelToken, options: options, queryParameters: queryParameters);

      Utils.cPrint("GET response :: ${response.data}");
      final apiRes = ApiResModel.fromJson(response.data ?? {});
      return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
    } on DioErrorException catch (e) {
      return ResModel.withError(message: e.errorResponse.message, error: e.errorResponse.statusCode);
    } on UnAuthorizedException catch (_) {
      // bool success = await _refreshToken();
      // if (success) {
      try {
        var response = await dio.get((baseUrl ?? dio.options.baseUrl) + path,
            onReceiveProgress: onReceiveProgress, cancelToken: cancelToken, options: options, queryParameters: queryParameters);

        Utils.cPrint("GET response :: ${response.data}");
        final apiRes = ApiResModel.fromJson(response.data ?? {});
        return ResModel(
          error: apiRes.error,
          message: apiRes.message,
          count: apiRes.count,
          data: apiRes.data is List ? apiRes.toJson() : apiRes.data,
        );
      } catch (e) {
        Utils.cPrint("ERROR AFTER REFRESH TOKEN $e");
        return ResModel.withError(message: e.toString(), error: HttpStatus.internalServerError);
      }
      // } else {
      //   _forceLogout();
      //   return ResModel.withError(
      //       message: "UnAuthorized", error: HttpStatus.unauthorized);
      // }
    } catch (e) {
      log(e.toString());
      return ResModel.withError(message: e.toString(), error: HttpStatus.internalServerError);
    }
  }

  ///[POST] We will use this method in order to process post requests
  Future<ResModel> post(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
    String? baseUrl,
    void Function(int, int)? onSendProgress,
    void Function(int, int)? onReceiveProgress,
    bool addRequestInterceptor = false,
    bool addAuthInterceptor = true,
  }) async {
    await _addRequestAndAuthInterceptor(addAuthInterceptor: addAuthInterceptor);

    try {
      final response = await dio.post((baseUrl ?? dio.options.baseUrl) + path,
          data: data,
          queryParameters: queryParameters,
          options: options,
          cancelToken: cancelToken,
          onReceiveProgress: onReceiveProgress,
          onSendProgress: onSendProgress);
      Utils.cPrint("POST response :: ${response.data}");

      // Handle error responses
      if (response.statusCode != null && response.statusCode! >= 400) {
        Utils.cPrint("API Error Response: ${response.statusCode} - ${response.data}");
        return ResModel.withError(
          message: response.data?['message'] ?? "API Error: ${response.statusCode} - ${response.statusMessage ?? 'Unknown error'}",
          error: response.statusCode,
          data: response.data,
        );
      }

      final apiRes = ApiResModel.fromJson(response.data ?? {});
      return ResModel(
        error: apiRes.error,
        message: apiRes.message,
        count: apiRes.count,
        data: apiRes.data is List ? apiRes.toJson() : apiRes.data,
      );
    } on DioErrorException catch (e) {
      return ResModel.withError(message: e.errorResponse.message, error: e.errorResponse.statusCode);
    } on UnAuthorizedException catch (_) {
      try {
        final response = await dio.post((baseUrl ?? dio.options.baseUrl) + path,
            data: data,
            queryParameters: queryParameters,
            options: options,
            cancelToken: cancelToken,
            onReceiveProgress: onReceiveProgress,
            onSendProgress: onSendProgress);
        Utils.cPrint("POST response :: ${response.data}");

        // Handle error responses
        if (response.statusCode != null && response.statusCode! >= 400) {
          return ResModel.withError(
            message: response.data?['message'] ?? "Something went wrong",
            error: response.statusCode,
            data: response.data,
          );
        }

        final apiRes = ApiResModel.fromJson(response.data ?? {});
        return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
      } catch (e) {
        Utils.cPrint("ERROR AFTER REFRESH TOKEN $e");
        return ResModel.withError(message: e.toString(), error: HttpStatus.internalServerError);
      }
    } catch (e) {
      log(e.toString());
      return ResModel.withError(message: e.toString(), error: HttpStatus.internalServerError);
    }
  }

  ///[PATCH] We will use this method in order to process post requests
  Future<ResModel> patch(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
    String? baseUrl,
    void Function(int, int)? onSendProgress,
    void Function(int, int)? onReceiveProgress,
    bool addRequestInterceptor = false,
    bool addAuthInterceptor = false,
  }) async {
    await _addRequestAndAuthInterceptor(
        // addRequestInterceptor: addRequestInterceptor,
        addAuthInterceptor: addAuthInterceptor);
    try {
      final response = await dio.patch<Map<String, dynamic>?>((baseUrl ?? dio.options.baseUrl) + path,
          data: data,
          queryParameters: queryParameters,
          options: options,
          cancelToken: cancelToken,
          onReceiveProgress: onReceiveProgress,
          onSendProgress: onSendProgress);
      Utils.cPrint("PATCH response :: ${response.data}");
      final apiRes = ApiResModel.fromJson(response.data ?? {});
      return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
    } on DioErrorException catch (e) {
      return ResModel.withError(message: e.errorResponse.message, error: e.errorResponse.statusCode);
    } on UnAuthorizedException catch (_) {
      // bool success = await _refreshToken();
      // if (success) {
      try {
        final response = await dio.patch((baseUrl ?? dio.options.baseUrl) + path,
            data: data,
            queryParameters: queryParameters,
            options: options,
            cancelToken: cancelToken,
            onReceiveProgress: onReceiveProgress,
            onSendProgress: onSendProgress);
        Utils.cPrint("PATCH response :: ${response.data}");
        final apiRes = ApiResModel.fromJson(response.data ?? {});
        return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
      } catch (e) {
        Utils.cPrint("ERROR AFTER REFRESH TOKEN $e");
        return ResModel.withError(message: e.toString(), error: HttpStatus.internalServerError);
      }
      // } else {
      //   _forceLogout();
      //   return ResModel.withError(
      //       message: "UnAuthorized", error: HttpStatus.unauthorized);
      // }
    } catch (e) {
      log(e.toString());
      return ResModel.withError(message: "Something went wrong", error: HttpStatus.internalServerError);
    }
  }

  ///[PUT] We will use this method in order to process post requests
  Future<ResModel> put(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
    String? baseUrl,
    void Function(int, int)? onSendProgress,
    void Function(int, int)? onReceiveProgress,
    bool addRequestInterceptor = false,
    bool addAuthInterceptor = false,
  }) async {
    await _addRequestAndAuthInterceptor(
        // addRequestInterceptor: addRequestInterceptor,
        addAuthInterceptor: addAuthInterceptor);
    try {
      final response = await dio.put<Map<String, dynamic>?>(
        (baseUrl ?? dio.options.baseUrl) + path,
        data: data,
        queryParameters: queryParameters,
        options: options,
        cancelToken: cancelToken,
      );
      Utils.cPrint("PUT response :: ${response.data}");
      final apiRes = ApiResModel.fromJson(response.data ?? {});
      return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
    } on DioErrorException catch (e) {
      return ResModel.withError(message: e.errorResponse.message, error: e.errorResponse.statusCode);
    } on UnAuthorizedException catch (_) {
      // bool success = await _refreshToken();
      // if (success) {
      try {
        final response = await dio.put(
          (baseUrl ?? dio.options.baseUrl) + path,
          data: data,
          queryParameters: queryParameters,
          options: options,
          cancelToken: cancelToken,
        );
        Utils.cPrint("PUT response :: ${response.data}");
        final apiRes = ApiResModel.fromJson(response.data ?? {});
        return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
      } catch (e) {
        Utils.cPrint("ERROR AFTER REFRESH TOKEN $e");
        return ResModel.withError(message: e.toString(), error: HttpStatus.internalServerError);
      }
      // } else {
      //   _forceLogout();
      //   return ResModel.withError(
      //       message: "UnAuthorized", error: HttpStatus.unauthorized);
      // }
    } catch (e) {
      log(e.toString());
      return ResModel.withError(message: "Something went wrong", error: HttpStatus.internalServerError);
    }
  }

  ///[DELETE] We will use this method in order to process post requests
  Future<ResModel> delete(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
    String? baseUrl,
    void Function(int, int)? onSendProgress,
    void Function(int, int)? onReceiveProgress,
    bool addRequestInterceptor = true,
    bool addAuthInterceptor = false,
  }) async {
    await _addRequestAndAuthInterceptor(
        // addRequestInterceptor: addRequestInterceptor,
        addAuthInterceptor: addAuthInterceptor);
    try {
      final response = await dio.delete<Map<String, dynamic>?>(
        (baseUrl ?? dio.options.baseUrl) + path,
        data: data,
        queryParameters: queryParameters,
        options: options,
        cancelToken: cancelToken,
      );
      Utils.cPrint("DELETE response :: ${response.data}");
      final apiRes = ApiResModel.fromJson(response.data ?? {});
      return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
    } on DioErrorException catch (e) {
      return ResModel.withError(message: e.errorResponse.message, error: e.errorResponse.statusCode);
    } on UnAuthorizedException catch (_) {
      // bool success = await _refreshToken();
      // if (success) {
      try {
        final response = await dio.delete(
          (baseUrl ?? dio.options.baseUrl) + path,
          data: data,
          queryParameters: queryParameters,
          options: options,
          cancelToken: cancelToken,
        );
        Utils.cPrint("DELETE response :: ${response.data}");
        final apiRes = ApiResModel.fromJson(response.data ?? {});
        return ResModel(error: apiRes.error, message: apiRes.message, data: apiRes.data is List ? apiRes.toJson() : apiRes.data);
      } catch (e) {
        Utils.cPrint("ERROR AFTER REFRESH TOKEN $e");
        return ResModel.withError(message: e.toString(), error: HttpStatus.internalServerError);
      }
      // } else {
      //   _forceLogout();
      //   return ResModel.withError(
      //       message: "UnAuthorized", error: HttpStatus.unauthorized);
      // }
    } catch (e) {
      log(e.toString());
      return ResModel.withError(message: "Something went wrong", error: HttpStatus.internalServerError);
    }
  }

  Future<void> _addRequestAndAuthInterceptor(
      {
      // required bool addRequestInterceptor,

      required bool addAuthInterceptor}) async {
    if (addAuthInterceptor) {
      _token = await _getAccessToken() ?? sl<ProfileCubit>().state.profileModel?.data?.apiToken ?? "";
      Utils.cPrint("this is token: $_token");
      if (!dio.interceptors.contains(AuthInterceptor(authToken: _token))) {
        dio.interceptors.add(AuthInterceptor(authToken: _token));
      }
    } else {
      if (dio.interceptors.contains(AuthInterceptor(authToken: _token))) {
        dio.interceptors.remove(AuthInterceptor(authToken: _token));
      }
    }
    if (!kIsWeb) {
      (dio.httpClientAdapter as IOHttpClientAdapter).createHttpClient =
          () => HttpClient()..badCertificateCallback = (X509Certificate cert, String host, int port) => true;
    }
  }
}

class ErrorResponse {
  final String message;
  final int statusCode;

  const ErrorResponse({
    required this.message,
    this.statusCode = 500,
  });

  factory ErrorResponse.fromMap(Map<String, dynamic> map) {
    return ErrorResponse(
      message: map['message'] ?? 'Something went wrong',
      statusCode: map['error'] ?? 500,
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'message': message,
      'status_code': statusCode,
    };
  }
}

class HeaderInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    options.headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-APP-PKG': 'com.remitsystem.remit',
    };
    return handler.next(options);
  }
}

class ErrorInterceptor extends Interceptor {
  final Dio dio;

  ErrorInterceptor(this.dio);

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    ErrorResponse response;
    try {
      response = ErrorResponse.fromMap((err.response?.data ?? {'message': err.response?.statusMessage ?? 'Something went wrong'}) as Map<String, dynamic>);
    } catch (_) {
      response = ErrorResponse(message: err.message ?? 'Something went wrong');
    }
    debugPrint('''
===== API ERROR =====
METHOD : ${err.requestOptions.method} ${err.requestOptions.uri}
STATUS : ${err.response?.statusCode ?? err.type}
TYPE   : ${err.type}
BODY   : ${err.response?.data}
MESSAGE: ${err.message}
=====================''');
    Utils.cPrint("ERROR STATUS CODE :: ${err.response?.statusCode ?? 600}");

    switch (err.type) {
      case DioExceptionType.badResponse:
        {
          switch (err.response?.statusCode) {
            case 401:
              {
                // if (err.requestOptions.path.contains('token/refresh')) {
                //   throw TokenExpiredException(
                //       errorResponse: response,
                //       requestOptions: err.requestOptions);
                // }

                throw UnAuthorizedException(errorResponse: response, requestOptions: err.requestOptions);
              }
            case 404:
              {
                throw UnAuthorizedException(errorResponse: response, requestOptions: err.requestOptions);
              }

            default:
              throw DioErrorException(errorResponse: response, requestOptions: err.requestOptions);
          }
        }
      default:
        throw DioErrorException(errorResponse: response, requestOptions: err.requestOptions);
    }
  }
}

class RequestInterceptor extends Interceptor {
  final String apiKey;

  RequestInterceptor({required this.apiKey});

  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    options.headers = {
      'token': apiKey, /* 'token': token */
    };
    return handler.next(options);
  }
}

class AuthInterceptor extends Interceptor {
  String? authToken;

  AuthInterceptor({
    this.authToken,
  });

  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    // var token = "";
    options.headers['X-Access-Token'] = authToken;
    // options.headers = {
    //   'token': authToken,
    // };
    return handler.next(options);
  }
}

class DioErrorException extends DioException {
  final ErrorResponse errorResponse;

  @override
  String toString() => errorResponse.message;

  DioErrorException({required this.errorResponse, required super.requestOptions});
}

class UnAuthorizedException extends DioException {
  final ErrorResponse errorResponse;

  @override
  String toString() => errorResponse.message;

  UnAuthorizedException({required this.errorResponse, required super.requestOptions});
}

// class TokenExpiredException extends DioException {
//   final ErrorResponse errorResponse;

//   @override
//   String toString() => errorResponse.message;

//   TokenExpiredException(
//       {required this.errorResponse, required super.requestOptions});
// }
