import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/models/id_name_model.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_loader_indicator.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/dahboard/bloc/address_cubit/address_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/address_cubit/address_state.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_state.dart';
import 'package:remit_management/modules/dahboard/models/profile_model.dart';
import 'package:remit_management/modules/dahboard/repo/address_repo.dart';

import '../../../core/common/widget/custom_future_search_dropdown.dart';
import '../../../core/common/widget/custom_searchable_dropdown.dart';
import '../../../core/utils/debouncer.dart';

class EditProfileScreen extends StatefulWidget {
  const EditProfileScreen({super.key});

  @override
  State<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final _formKey = GlobalKey<FormState>();

  // Controllers
  late TextEditingController _emailController;
  late TextEditingController _firstNameController;
  late TextEditingController _lastNameController;
  late TextEditingController _phoneController;
  late TextEditingController _dobController;
  late TextEditingController _streetController;
  late TextEditingController _suburbController;
  late TextEditingController _postcodeController;
  late TextEditingController _stateController;
  late TextEditingController _countryController;

  final DeBouncer _deBouncer = DeBouncer(milliseconds: 500);

  @override
  void initState() {
    super.initState();
    // Initialize controllers with late to ensure context is ready if needed
    final pData = context.read<ProfileCubit>().state.profileModel?.data;

    _emailController = TextEditingController(text: pData?.email ?? '');
    _firstNameController = TextEditingController(text: pData?.firstName ?? '');
    _lastNameController = TextEditingController(text: pData?.lastName ?? '');
    _phoneController = TextEditingController(text: pData?.number ?? '');
    _dobController = TextEditingController(
      text: pData?.dob != null ? DateFormat('dd/MM/yyyy').format(pData!.dob!) : '',
    );
    _streetController = TextEditingController(text: pData?.street ?? '');
    _suburbController = TextEditingController(text: pData?.suburb ?? '');
    _postcodeController = TextEditingController(text: pData?.postcode ?? '');
    _stateController = TextEditingController(text: pData?.state ?? '');
    _countryController = TextEditingController();
  }

  @override
  void dispose() {
    _emailController.dispose();
    _firstNameController.dispose();
    _lastNameController.dispose();
    _phoneController.dispose();
    _dobController.dispose();
    _streetController.dispose();
    _suburbController.dispose();
    _postcodeController.dispose();
    _stateController.dispose();
    _countryController.dispose();

    super.dispose();
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now().subtract(const Duration(days: 6570)),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() {
        _dobController.text = DateFormat('dd/MM/yyyy').format(picked);
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(
          create: (context) => AddressCubit()
            ..getCountries()
            ..getStates()
            ..getSuburbs(""),
        ),
        BlocProvider.value(
          value: context.read<ProfileCubit>(),
        ),
      ],
      child: Scaffold(
        appBar: AppBar(title: const Text("Edit Profile"), elevation: 0),
        body: SingleChildScrollView(
          padding: const EdgeInsets.all(16.0),
          child: Form(
            key: _formKey,
            child: BlocConsumer<ProfileCubit, ProfileState>(
              listener: (ctx, state) {
                if (state.isEditLoading == AppState.success) {
                  sl<ProfileCubit>().resetEditState();
                  sl<ProfileCubit>().getProfile();
                  ctx.pop();
                  AppSnackbar.showSnackBar(context: context, message: state.message ?? "Profile updated successfully", type: SnackBarType.success);
                } else if (state.isEditLoading == AppState.error) {
                  AppSnackbar.showSnackBar(context: ctx, message: state.message ?? "Failed to update profile", type: SnackBarType.error);
                }
              },
              builder: (context, profileState) {
                if (profileState.isLoading == AppState.loading) {
                  return AppLoaderIndicator();
                }

                return Column(
                  children: [
                    CustomFormFieldWidget(
                      title: "Email Address",
                      controller: _emailController,
                      readOnly: true,
                      prefixIcon: Icons.email_outlined,
                      textStyle: AppText.bodyMedium400.copyWith(color: AppColor.gray400),
                    ),
                    16.sBHh,
                    Row(
                      children: [
                        Expanded(
                          child: CustomFormFieldWidget(
                            title: "First Name",
                            controller: _firstNameController,
                            validator: (v) => AppValidator.validateRequired(_firstNameController.text, 'First Name'),
                          ),
                        ),
                        12.sBWw,
                        Expanded(
                          child: CustomFormFieldWidget(
                            title: "Last Name",
                            controller: _lastNameController,
                            validator: (v) => AppValidator.validateRequired(_lastNameController.text, 'Last Name'),
                          ),
                        ),
                      ],
                    ),
                    16.sBHh,
                    CustomFormFieldWidget(
                      title: "Phone Number",
                      controller: _phoneController,
                      keyboardType: TextInputType.phone,
                      validator: (v) => AppValidator.validatePhone(_phoneController.text, 'Phone Number'),
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                    ),
                    16.sBHh,
                    GestureDetector(
                      onTap: () => _selectDate(context),
                      child: AbsorbPointer(
                        child: CustomFormFieldWidget(
                          title: "Date of Birth",
                          controller: _dobController,
                          prefixIcon: Icons.calendar_today_outlined,
                          validator: (v) => AppValidator.validateRequired(_dobController.text, 'Date of Birth'),
                        ),
                      ),
                    ),
                    16.sBHh,
                    CustomFormFieldWidget(
                      title: "Street Address",
                      controller: _streetController,
                      validator: (v) => AppValidator.validateRequired(_streetController.text, 'Street Address'),
                    ),
                    16.sBHh,
                    CustomFormFieldWidget(
                      title: "Postcode",
                      controller: _postcodeController,
                      keyboardType: TextInputType.number,
                      validator: (v) => AppValidator.validateRequired(_postcodeController.text, 'Postcode'),
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                    ),
                    16.sBHh,

                    // Suburb, State, Country Section
                    BlocBuilder<AddressCubit, AddressState>(
                      builder: (context, addrState) {
                        // Setup Country Controller initial text once data loads
                        if (_countryController.text.isEmpty && addrState.countriesData != null) {
                          final currentCountryId = profileState.profileModel?.data?.countryListId.toString();
                          _countryController.text =
                              addrState.countriesData?.data?.firstWhere((e) => e.id == currentCountryId, orElse: () => IdNameModel(name: '', id: '0')).name ??
                                  '';
                        }
                        // print(_countryController.text);
                        return Column(
                          children: [
                            // _buildDropdown(
                            //   loading: addrState.isSuburbLoading == AppState.loading,
                            //   title: 'Suburb',
                            //   controller: _suburbController,
                            //   items: addrState.suburbsData?.data?.map((e) => e.name).toList() ?? [],
                            // ),
//this needs to be changes
                            CustomFutureSearchDropdown(
                              controller: _suburbController,
                              title: 'Suburb',
                              hint: 'Select Suburb',
                              validator: (v) => AppValidator.validateRequired(_suburbController.text, 'Suburb'),
                              fetchItems: (query) async {
                                final completer = Completer<List<String>>();

                                _deBouncer.run(() async {
                                  final res = await sl<AddressRepo>().getSuburbs(query);

                                  final items = res.fold(
                                    (l) => <String>[],
                                    (r) => r.data?.map((e) => e.name).toList() ?? <String>[],
                                  );

                                  if (!completer.isCompleted) {
                                    completer.complete(items);
                                  }
                                });

                                return completer.future;
                              },
                            ),
                            16.sBHh,
                            _buildDropdown(
                              loading: addrState.isStateLoading == AppState.loading,
                              title: 'State',
                              controller: _stateController,
                              items: addrState.statesData?.data?.map((e) => e.name).toList() ?? [],
                            ),
                            16.sBHh,
                            _buildDropdown(
                              loading: addrState.isCountryLoading == AppState.loading,
                              title: 'Country',
                              disableDropDown: true,
                              controller: _countryController,
                              items: addrState.countriesData?.data?.map((e) => e.name).toList() ?? [],
                            ),
                            16.sBHh,
                          ],
                        );
                      },
                    ),

                    32.sBHh,

                    // Submit Button
                    BlocBuilder<AddressCubit, AddressState>(
                      builder: (context, addrState) {
                        final isAddressLoading = addrState.isCountryLoading == AppState.loading ||
                            addrState.isStateLoading == AppState.loading ||
                            addrState.isSuburbLoading == AppState.loading;

                        return AppButton(
                          isDisabled: isAddressLoading,
                          isLoading: profileState.isEditLoading == AppState.loading || profileState.isLoading == AppState.loading,
                          title: 'Save',
                          onPressed: () => _handleUpdate(context, profileState, addrState),
                        );
                      },
                    ),
                    30.sBHh,
                  ],
                );
              },
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildDropdown(
      {required bool loading, required String title, required TextEditingController controller, required List<String> items, bool disableDropDown = false}) {
    if (loading) return const AppLoaderIndicator();
    return CustomSearchableDropdown(
      controller: controller,
      title: title,
      hint: 'Select $title',
      validator: (v) => AppValidator.validateRequired(controller.text, title),
      items: items,
      disableDropdown: disableDropDown,
    );
  }

  void _handleUpdate(BuildContext context, ProfileState pState, AddressState aState) {
    if (_formKey.currentState!.validate()) {
      final countryId = aState.countriesData?.data?.firstWhere((e) => e.name == _countryController.text, orElse: () => IdNameModel(id: '0', name: '')).id;

      final param = ProfileParam(
        email: _emailController.text,
        firstName: _firstNameController.text,
        lastName: _lastNameController.text,
        phone: _phoneController.text,
        dob: _dobController.text,
        street: _streetController.text,
        suburb: _suburbController.text,
        postcode: _postcodeController.text,
        state: _stateController.text,
        countryListId: int.tryParse('$countryId') ?? 0,
      );

      context.read<ProfileCubit>().editProfile(param);
    }
  }
}
