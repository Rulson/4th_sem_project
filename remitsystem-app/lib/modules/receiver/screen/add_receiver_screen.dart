import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/common/widget/custom_searchable_dropdown.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/receiver/bloc/add_receiver_cubit/add_receiver_cubit.dart';
import 'package:remit_management/modules/receiver/bloc/add_receiver_cubit/add_receiver_state.dart';
import 'package:remit_management/modules/receiver/bloc/country_cubit.dart';
import 'package:remit_management/modules/receiver/bloc/country_state.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';
import 'package:remit_management/modules/receiver/model/receiver_pm.dart';

import '../bloc/bank_cubit/bank_cubit.dart';
import '../bloc/bank_cubit/bank_state.dart';

class AddReceiverScreen extends StatelessWidget {
  const AddReceiverScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final Map<String, dynamic>? extra = GoRouterState.of(context).extra as Map<String, dynamic>?;
    final isEdit = extra?['isEdit'] as bool? ?? false;
    final receiver = extra?['receiver'] as ReceiverData?;

    return MultiBlocProvider(
      providers: [
        BlocProvider(
          create: (context) => CountryCubit(),
        ),
        BlocProvider(
          create: (context) => AddReceiverCubit(),
        ),
        BlocProvider(
          create: (context) => BankCubit(),
        ),
      ],
      child: AddReceuverView(
        isEdit: isEdit,
        receiver: receiver,
      ),
    );
  }
}

class AddReceuverView extends StatefulWidget {
  final bool isEdit;
  final ReceiverData? receiver;
  const AddReceuverView({super.key, this.isEdit = false, this.receiver});

  @override
  State<AddReceuverView> createState() => _AddReceuverViewState();
}

class _AddReceuverViewState extends State<AddReceuverView> {
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _mobNumberController = TextEditingController();
  final TextEditingController _suburbController = TextEditingController();
  final TextEditingController _districtController = TextEditingController();
  final TextEditingController _provinceController = TextEditingController();
  final TextEditingController _accountNameController = TextEditingController();
  final TextEditingController _accountNumberController = TextEditingController();
  final TextEditingController _bankController = TextEditingController();
  final TextEditingController _postCodeController = TextEditingController();
  final TextEditingController _bsbController = TextEditingController();
  final TextEditingController _streetController = TextEditingController();

  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    context.read<CountryCubit>()
      ..getProvinceList()
      ..getDistrictList();
    context.read<BankCubit>().getBankList();

    if (widget.isEdit && widget.receiver != null) {
      _firstNameController.text = widget.receiver!.firstName ?? '';
      _lastNameController.text = widget.receiver!.lastName ?? '';
      _mobNumberController.text = widget.receiver!.number ?? '';
      _suburbController.text = widget.receiver!.suburb ?? '';
      _districtController.text = widget.receiver!.district ?? '';
      _provinceController.text = widget.receiver!.state ?? '';
      _accountNameController.text = widget.receiver!.accountName ?? '';
      _accountNumberController.text = widget.receiver!.accountNo ?? '';
      _bankController.text = widget.receiver!.bankName ?? '';
      _postCodeController.text = widget.receiver!.postcode ?? '';
      _bsbController.text = widget.receiver!.bsb ?? '';
      _streetController.text = widget.receiver!.street ?? '';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColor.white,
      appBar: CustomAppBar(
          // title: widget.isEdit ? "Edit Receiver" : "Add Receiver",
          ),
      bottomNavigationBar: Padding(
        padding: EdgeInsets.fromLTRB(20.w, 0, 20.w, 24.h),
        child: BlocConsumer<AddReceiverCubit, AddReceiverState>(
          listener: (context, state) {
            if (state.isLoading == AppState.success) {
              if (!widget.isEdit && state.newReceiver != null) {
                context.pop(state.newReceiver);
              } else {
                context.pop();
              }
              context.read<AddReceiverCubit>().resetState();
              AppSnackbar.showSnackBar(
                context: context,
                message: widget.isEdit ? "Receiver Updated Successfully" : "Receiver Added Successfully",
                type: SnackBarType.success,
              );
            } else if (state.isLoading == AppState.error) {
              context.read<AddReceiverCubit>().resetState();
              AppSnackbar.showSnackBar(context: context, message: state.message, type: SnackBarType.error);
            }
          },
          builder: (context, state) {
            return AppButton(
              height: 54.h,
              trailingIcon: SAppAssets.iconArrowRight,
              isLoading: state.isLoading == AppState.loading,
              onPressed: () {
                if (_formKey.currentState!.validate()) {
                  final params = ReceiverPm(
                    firstName: _firstNameController.text,
                    lastName: _lastNameController.text,
                    number: _mobNumberController.text,
                    suburb: _suburbController.text,
                    district: _districtController.text,
                    state: _provinceController.text,
                    postcode: _postCodeController.text,
                    countryListId: "154",
                    bsb: _bsbController.text,
                    street: _streetController.text,
                    accountName: _accountNameController.text,
                    accountNo: _accountNumberController.text,
                    bankName: _bankController.text,
                  );
                  if (widget.isEdit && widget.receiver != null) {
                    context.read<AddReceiverCubit>().updateReceiver(
                          params,
                          widget.receiver!.beneficiaryId!,
                          context.read<ReceiverListingCubit>(),
                        );
                    context.pop();
                  } else {
                    context.read<AddReceiverCubit>().addReceiver(
                          params,
                          context.read<ReceiverListingCubit>(),
                        );
                  }
                }
              },
              title: widget.isEdit ? 'Update Receiver' : 'Continue',
            );
          },
        ),
      ),
      body: BlocBuilder<CountryCubit, CountryState>(
        builder: (context, countryState) {
          return Form(
            key: _formKey,
            child: SingleChildScrollView(
              padding: EdgeInsets.symmetric(horizontal: 20.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  12.sBHh,
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              widget.isEdit ? "Edit Receiver" : "Add a Receiver",
                              style: AppText.headlineMedium400.copyWith(
                                color: AppColor.gray1000,
                                fontWeight: FontWeight.w800,
                              ),
                            ),
                            8.sBHh,
                            Text(
                              "Enter the receiver's details to send money quickly and securely",
                              style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
                            ),
                          ],
                        ),
                      ),
                      16.sBWw,
                      Image.asset(
                        SAppAssets.imageReceiverIllustration,
                        height: 64.h,
                        fit: BoxFit.contain,
                      ),
                    ],
                  ),
                  28.sBHh,
                  _SectionHeader(title: "General Information"),
                  20.sBHh,
                  CustomFormFieldWidget(
                    title: 'First Name',
                    hint: 'Enter First Name',
                    controller: _firstNameController,
                    validator: (v) => AppValidator.validateRequired(_firstNameController.text, "First Name"),
                  ),
                  16.sBHh,
                  CustomFormFieldWidget(
                    title: 'Last Name',
                    hint: 'Enter Last Name',
                    controller: _lastNameController,
                    validator: (v) => AppValidator.validateRequired(_lastNameController.text, "Last Name"),
                  ),
                  16.sBHh,
                  CustomFormFieldWidget(
                    title: 'Mobile Number',
                    hint: 'Enter Mobile Number',
                    controller: _mobNumberController,
                    keyboardType: TextInputType.phone,
                    validator: (v) => AppValidator.validateRequired(_mobNumberController.text, "Mobile Number"),
                  ),
                  28.sBHh,
                  _SectionHeader(title: "Address Information"),
                  20.sBHh,
                  CustomFormFieldWidget(
                    title: 'Suburb',
                    hint: 'Enter Suburb',
                    controller: _suburbController,
                    validator: (v) => AppValidator.validateRequired(_suburbController.text, "Suburb"),
                  ),
                  16.sBHh,
                  CustomSearchableDropdown(
                    controller: _districtController,
                    title: 'District',
                    hint: 'Select District',
                    validator: (v) => AppValidator.validateRequired(_districtController.text, "District"),
                    items: countryState.districtData?.data?.map<String>((e) => e.name ?? "").toList() ?? [],
                  ),
                  16.sBHh,
                  CustomSearchableDropdown(
                    controller: _provinceController,
                    title: 'Province',
                    hint: 'Select Province',
                    validator: (v) => AppValidator.validateRequired(_provinceController.text, "Province"),
                    items: countryState.proviceData?.data?.map<String>((e) => e.name ?? "").toList() ?? [],
                  ),
                  16.sBHh,
                  CustomFormFieldWidget(
                    title: 'Postcode',
                    hint: 'Enter Postcode',
                    controller: _postCodeController,
                    keyboardType: TextInputType.number,
                    validator: (v) => AppValidator.validateRequired(_postCodeController.text, "Postcode"),
                  ),
                  28.sBHh,
                  _SectionHeader(title: "Account Informations"),
                  20.sBHh,
                  CustomFormFieldWidget(
                    title: 'Account Name',
                    hint: 'Enter Account Name',
                    controller: _accountNameController,
                    validator: (v) => AppValidator.validateRequired(_accountNameController.text, "Account Name"),
                  ),
                  16.sBHh,
                  CustomFormFieldWidget(
                    title: 'Account Number',
                    hint: 'Enter Account Number',
                    controller: _accountNumberController,
                    keyboardType: TextInputType.number,
                    validator: (v) => AppValidator.validateRequired(_accountNumberController.text, "Account Number"),
                  ),
                  16.sBHh,
                  BlocBuilder<BankCubit, BankState>(builder: (context, state) {
                    return CustomSearchableDropdown(
                      controller: _bankController,
                      title: 'Select Bank',
                      hint: '',
                      validator: (v) => AppValidator.validateRequired(_bankController.text, "Bank"),
                      items: state.bankData?.data?.map<String>((e) => e.name ?? "").toList() ?? [],
                    );
                  }),
                  16.sBHh,
                  CustomFormFieldWidget(
                    title: 'Branch name*',
                    controller: _bsbController,
                    validator: (v) => AppValidator.validateRequired(_bsbController.text, "Branch name"),
                    hint: "Branch name",
                  ),
                  32.sBHh,
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class _SectionHeader extends StatelessWidget {
  final String title;
  const _SectionHeader({required this.title});

  @override
  Widget build(BuildContext context) {
    return Text(
      title,
      style: AppText.bodyMedium700.copyWith(color: AppColor.gray1000),
    );
  }
}
