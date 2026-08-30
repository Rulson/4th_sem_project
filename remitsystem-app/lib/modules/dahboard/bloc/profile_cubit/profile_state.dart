import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/modules/dahboard/models/profile_model.dart';

class ProfileState extends Equatable {
  final String? message;
  final AppState isLoading;
  final AppState isEditLoading;
  final ResModel<ProfileModel>? profileModel;
  const ProfileState({required this.isLoading, this.profileModel, this.message, required this.isEditLoading});

  factory ProfileState.initial() {
    return ProfileState(isLoading: AppState.initial, isEditLoading: AppState.initial);
  }

  ProfileState copyWith({AppState? isLoading, ResModel<ProfileModel>? profileModel, String? message, AppState? isEditLoading}) {
    return ProfileState(
      isLoading: isLoading ?? this.isLoading,
      isEditLoading: isEditLoading ?? this.isEditLoading,
      profileModel: profileModel ?? this.profileModel,
      message: message,
    );
  }

  @override
  List<Object?> get props => [isLoading, profileModel, message, isEditLoading];
}
