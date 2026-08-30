import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/routes/app_routes.dart' show AppRoutes;
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/receiver/bloc/add_receiver_cubit/add_receiver_cubit.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';

class RecipientsSingleScreen extends StatelessWidget {
  const RecipientsSingleScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final receiver = GoRouterState.of(context).extra as ReceiverData?;
    debugPrint('Editing suburb: ${receiver?.street}');
    return BlocProvider(
      create: (context) => AddReceiverCubit(),
      child: Builder(
        builder: (context) => Scaffold(
          backgroundColor: AppColor.white,
          body: SafeArea(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Align(
                      alignment: Alignment.topLeft,
                      child: IconButton(
                        icon: const Icon(Icons.arrow_back_ios_new),
                        onPressed: () => Navigator.of(context).pop(),
                      ),
                    ),
                    const SizedBox(height: 8),
                    CircleAvatar(
                      radius: 36,
                      backgroundColor: AppColor.gray200,
                      child: Text(
                        _getInitials(receiver),
                        style: AppText.titleMedium700,
                      ),
                    ),
                    const SizedBox(height: 16),
                    Text(
                      receiver?.fullName ?? '-',
                      style: AppText.headlineSmall400,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      receiver?.beneficiaryId?.toString() ?? '-',
                      style: AppText.bodyMedium400,
                    ),
                    const SizedBox(height: 24),
                    Container(
                      width: double.infinity,
                      margin: const EdgeInsets.symmetric(horizontal: 0),
                      padding: const EdgeInsets.all(0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const SizedBox(height: 8),
                          Text('Details', style: AppText.titleMedium700.copyWith(fontSize: 18)),
                          const SizedBox(height: 16),
                          _labelValue('Receiver ID', receiver?.beneficiaryId?.toString()),
                          const SizedBox(height: 16),
                          _labelValue('First Name', receiver?.firstName),
                          const SizedBox(height: 16),
                          _labelValue('Last Name', receiver?.lastName),
                          const SizedBox(height: 16),
                          _labelValue('Mobile Number', receiver?.number),
                          const SizedBox(height: 28),
                          Text('Account Details', style: AppText.titleMedium700.copyWith(fontSize: 18)),
                          const SizedBox(height: 16),
                          _labelValue('Account Name', receiver?.accountName),
                          const SizedBox(height: 16),
                          _labelValue('Account Number', receiver?.accountNo),
                          const SizedBox(height: 16),
                          _labelValue('Bank Name', receiver?.bankName),
                          const SizedBox(height: 16),
                          _labelValue('Branch', receiver?.bsb),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
          bottomNavigationBar: Padding(
            padding: const EdgeInsets.fromLTRB(20, 0, 20, 24),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () {
                          context.push(AppRoutes.addReceiver, extra: {
                            'receiver': receiver,
                            'isEdit': true,
                          });
                        },
                        style: OutlinedButton.styleFrom(
                          side: BorderSide(color: AppColor.gray200, width: 2),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          backgroundColor: Colors.white,
                        ),
                        child: Text(
                          'Edit',
                          style: TextStyle(
                            color: Colors.black,
                            fontWeight: FontWeight.w500,
                            fontSize: 16,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: () {
                          context.push(AppRoutes.sendMoney, extra: receiver);
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppColor.primary,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          elevation: 0,
                        ),
                        child: Text(
                          'Send money',
                          style: TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.w500,
                            fontSize: 16,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Center(
                  child: Container(
                    width: 80,
                    height: 6,
                    decoration: BoxDecoration(
                      color: Colors.black,
                      borderRadius: BorderRadius.circular(3),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  String _getInitials(ReceiverData? receiver) {
    if (receiver == null) return '';
    final first = receiver.firstName?.isNotEmpty == true ? receiver.firstName![0] : '';
    final last = receiver.lastName?.isNotEmpty == true ? receiver.lastName![0] : '';
    return (first + last).toUpperCase();
  }

  Widget _labelValue(String label, String? value) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: AppText.bodyMedium400.copyWith(color: AppColor.gray400)),
        const SizedBox(height: 4),
        Text(value ?? '-', style: AppText.titleMedium500.copyWith(color: AppColor.gray800)),
      ],
    );
  }
}
