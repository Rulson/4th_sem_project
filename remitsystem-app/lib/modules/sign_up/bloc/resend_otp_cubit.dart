import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/sign_up/bloc/resend_otp_state.dart';
import 'package:remit_management/modules/sign_up/models/param/resend_otp_param.dart';
import 'package:remit_management/modules/sign_up/repo/resend_otp_rp.dart';

class ResendOtpCubit extends Cubit<ResendOtpState> {
  ResendOtpCubit() : super(ResendOtpState.initial());

  void resendOtp(String email) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<ResendOtpRepo>().resendOtp(
      params: ResendOtpParam(email: email),
    );

    emit(res.fold(
      (l) => state.copyWith(isLoading: AppState.error, message: l),
      (r) => state.copyWith(isLoading: AppState.success, message: r.message),
    ));
  }
  

  void resendEmailActivationCode(String email)async{
    emit(state.copyWith(isLoading: AppState.loading));

  
    final res = await sl<ResendOtpRepo>().resendEmailActivationCode(
         params: ResendOtpParam(email: email),
    );

    emit(res.fold(
      (l) => state.copyWith(isLoading: AppState.error, message: l),
      (r) => state.copyWith(isLoading: AppState.success, message: r.message),
    ));
  }

  void resetState() => emit(ResendOtpState.initial());



}
