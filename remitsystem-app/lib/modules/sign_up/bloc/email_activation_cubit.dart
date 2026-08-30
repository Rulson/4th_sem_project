import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/sign_up/bloc/email_activation_state.dart';
import 'package:remit_management/modules/sign_up/models/param/create_ac_param.dart';
import 'package:remit_management/modules/sign_up/repo/create_ac_rp.dart';

class EmailActivationCubit extends Cubit<EmailActivationState> {
  EmailActivationCubit() : super(EmailActivationState.initial());

  void emailActivation(EmailActivationParam params) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<VerifyEmailNewRepo>().emailActivation(params: params);

    emit(res.fold((l) {
      return state.copyWith(isLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(isLoading: AppState.success, message: r.message);
    }));
  }


  void resetState() => emit(EmailActivationState.initial());
}
