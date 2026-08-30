
// import 'package:dartz/dartz.dart';
// import 'package:remit_management/core/common/models/res_model.dart';

// class ResHandler<T> {

//   Either<String, ResModel<T>> handleApiResponse(ResModel res, T convertedData) {
//   if (res.error == 0) {
//     return Right(ResModel(
//       data: res.data != null ? convertedData  : null,
//       message: res.message,
//       error: res.error,
//     ));
//   } else {
//     return Left(res.message ?? "Something went wrong");
//   }
// }
// }