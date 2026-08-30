import 'package:equatable/equatable.dart';

import '../../../../../../core/common/app_state.dart';
import '../../../../../../core/common/models/res_model.dart';


class ReadNotificationState extends Equatable {
  final String? message;
  final AppState isLoading;
  final ResModel<String>? result;
  const ReadNotificationState({required this.isLoading, this.result, this.message});

  factory ReadNotificationState.initial() {
    return ReadNotificationState(isLoading: AppState.initial);
  }

  ReadNotificationState copyWith({AppState? isLoading, ResModel<String>? result, String? message}) {
    return ReadNotificationState(
      isLoading: isLoading ?? this.isLoading,
      result: result ?? this.result,
      message: message,
    );
  }

  @override
  List<Object?> get props => [isLoading, result, message];
}
