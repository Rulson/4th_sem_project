import 'package:get_it/get_it.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/dahboard/repo/home_repo.dart';
import 'package:remit_management/modules/dahboard/repo/profile_repo.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/repo/change_password_repo.dart';
import 'package:remit_management/modules/pin/repo/pin_repo.dart';
import 'package:remit_management/modules/send_money/repository/send_money_repo.dart';
import 'package:remit_management/modules/sign_in/repo/sign_in_rp.dart';
import 'package:remit_management/modules/sign_up/repo/create_ac_rp.dart';
import 'package:remit_management/modules/sign_up/repo/resend_otp_rp.dart';

import '../../modules/dahboard/bloc/address_cubit/address_cubit.dart';
import '../../modules/dahboard/repo/address_repo.dart';
import '../../modules/dahboard/screen/notification/bloc/notification_list_cubit.dart';
import '../../modules/receiver/repo/receiver_repo.dart';

final sl = GetIt.instance;

Future<void> initializeDependencies() async {
  // API
  sl.registerSingleton<ApiService>(ApiService());

//cubit
  sl.registerSingleton<ProfileCubit>(ProfileCubit());
  sl.registerSingleton<HomeCubit>(HomeCubit());
  sl.registerSingleton<AddressCubit>(AddressCubit());
  sl.registerLazySingleton(() => NotificationListCubit());

//repo
  sl.registerSingleton<SignInRp>(SignInRpImpl());
  sl.registerSingleton<PinRepo>(PinRepoImpl());
  sl.registerSingleton<HomeRepo>(HomeRepoImpl());
  sl.registerSingleton<ProfileRepo>(ProfileRepoImpl());
  sl.registerSingleton<ReceiverRepo>(ReceiverRepoImpl());
  sl.registerSingleton<SendMoneyRepo>(SendMoneyRepoImpl());
  sl.registerSingleton<CheckEmailAvailabilityAndSendOtpRepo>(CheckEmailAvailabilityAndSendOtpRepoImpl());
  sl.registerSingleton<VerifyEmailNewRepo>(VerifyEmailNewRepoImpl());
  sl.registerSingleton<RegisterRepo>(RegisterRepoImpl());
  sl.registerSingleton<ChangePasswordRepo>(ChangePasswordRepoImpl());
  sl.registerSingleton<AddressRepo>(AddressRepoImpl());
  sl.registerSingleton<ResendOtpRepo>(ResendOtpRepoImpl());
}
