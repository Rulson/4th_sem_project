import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/receiver/bloc/add_receiver_cubit/add_receiver_state.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/receiver/model/receiver_pm.dart';
import 'package:remit_management/modules/receiver/repo/receiver_repo.dart';

class AddReceiverCubit extends Cubit<AddReceiverState> {
  AddReceiverCubit() : super(AddReceiverState.initial());

  void addReceiver(ReceiverPm param, ReceiverListingCubit receiverListingCubit) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<ReceiverRepo>().addReceiver(param);

    res.fold((l) {
      String errorMessage = _extractErrorMessage(l);

      emit(state.copyWith(isLoading: AppState.error, message: errorMessage));
    }, (r) async {
      receiverListingCubit.getReceiverList();

      final receiverList = receiverListingCubit.state.receiverList?.data;
      final newReceiver = receiverList != null && receiverList.isNotEmpty ? receiverList.last : null;

      emit(state.copyWith(
        isLoading: AppState.success,
        message: r.message,
        newReceiver: newReceiver,
      ));
    });
  }

  void updateReceiver(ReceiverPm param, dynamic beneficiaryId, ReceiverListingCubit receiverListingCubit) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<ReceiverRepo>().updateReceiver(param, beneficiaryId);

    res.fold((l) {
      String errorMessage = _extractErrorMessage(l);
      emit(state.copyWith(isLoading: AppState.error, message: errorMessage));
    }, (r) async {
      receiverListingCubit.getReceiverList();
      emit(state.copyWith(
        isLoading: AppState.success,
        message: r.message,
      ));
    });
  }

  void resetState() {
    emit(AddReceiverState.initial());
  }

  /// Extract meaningful error message from the error response
  String _extractErrorMessage(dynamic error) {
    if (error is Map<String, dynamic>) {
      // Handle structured error response like the one in your log
      if (error.containsKey('response') && error['response'] is Map<String, dynamic>) {
        Map<String, dynamic> response = error['response'];
        List<String> errorMessages = [];

        // Extract field-specific errors
        response.forEach((key, value) {
          if (value is List) {
            for (var errorMsg in value) {
              errorMessages.add('$key: $errorMsg');
            }
          } else if (value is String) {
            errorMessages.add('$key: $value');
          }
        });

        if (errorMessages.isNotEmpty) {
          return errorMessages.join('\n');
        }
      }

      // Handle top-level message
      // if (error.containsKey('message')) {
      //   return error['message'].toString();
      // }
    }

    // Fallback to string representation
    return error.toString();
  }
}
