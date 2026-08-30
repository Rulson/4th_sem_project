import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:image_picker/image_picker.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/send_money/widget/upload_widget.dart';
import 'package:remit_management/modules/sign_up/screens/document_verification_page.dart';

import '../../../core/common/app_state.dart';
import '../../../core/common/widget/custom_future_search_dropdown.dart';
import '../../../core/common/widget/custom_searchable_dropdown.dart';
import '../../../core/locator/locator.dart';
import '../../../core/utils/app_loader_indicator.dart';
import '../../../core/utils/debouncer.dart';
import '../../dahboard/bloc/address_cubit/address_cubit.dart';
import '../../dahboard/bloc/address_cubit/address_state.dart';
import '../../dahboard/repo/address_repo.dart';

class FindYouPage extends StatelessWidget {
  final String firstName;
  final String lastName;
  final String phoneNumber;
  final String dob;
  final String gender;

  const FindYouPage({
    super.key,
    required this.firstName,
    required this.lastName,
    required this.phoneNumber,
    required this.dob,
    required this.gender,
  });

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => AddressCubit()
        // ..getCountries()
        ..getStates(),
      child: _FindYouView(
        firstName: firstName,
        lastName: lastName,
        phoneNumber: phoneNumber,
        dob: dob,
        gender: gender,
      ),
    );
  }
}

class _FindYouView extends StatefulWidget {
  final String firstName;
  final String lastName;
  final String phoneNumber;
  final String dob;
  final String gender;

  const _FindYouView({
    required this.firstName,
    required this.lastName,
    required this.phoneNumber,
    required this.dob,
    required this.gender,
  });

  @override
  State<_FindYouView> createState() => _FindYouViewState();
}

class _FindYouViewState extends State<_FindYouView> {
  final _streetController = TextEditingController();
  final _suburbController = TextEditingController();
  final _postalCodeController = TextEditingController();
  final _stateController = TextEditingController();
  // final _countryController = TextEditingController();

  final _formKey = GlobalKey<FormState>();

  String? _addressOfProof;

  final _imagePicker = ImagePicker();

  final DeBouncer _deBouncer = DeBouncer(milliseconds: 500);

  @override
  void dispose() {
    _streetController.dispose();
    _suburbController.dispose();
    _postalCodeController.dispose();
    _stateController.dispose();
    // _countryController.dispose();
    super.dispose();
  }

  void _onNext() {
    if (_formKey.currentState!.validate()) {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (_) => DocumentVerificationPage(
            firstName: widget.firstName,
            lastName: widget.lastName,
            phoneNumber: widget.phoneNumber,
            dob: widget.dob,
            // countryOfBirth: _countryController.text,
            gender: widget.gender,
            street: _streetController.text,
            suburb: _suburbController.text,
            postalCode: _postalCodeController.text,
            stateAddress: _stateController.text,
            addressOfProof: _addressOfProof ?? '',
          ),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColor.white,
      appBar: AppBar(
        backgroundColor: AppColor.white,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => Navigator.pop(context),
        ),
        actions: [
          Padding(
            padding: EdgeInsets.only(right: 22.w),
            child: Text(
              "2/3",
              style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
            ),
          ),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: EdgeInsets.symmetric(horizontal: 16.w),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                10.sBHh,
                Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Where Can We Find You?",
                            style: AppText.titleExtraLargeBold.copyWith(fontSize: 22, color: AppColor.black),
                          ),
                          Text(
                            "Enter your details as shown on your official ID",
                            style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                16.sBHh,
                CustomFormFieldWidget(
                  title: 'Unit/Street No.',
                  controller: _streetController,
                  validator: (value) => AppValidator.validateRequired(_streetController.text, 'Unit/Street No.'),
                ),
                20.sBHh,
                // CustomSearchableDropdown(controller: _suburbController, items: items, hint: hint, title: title)

                BlocBuilder<AddressCubit, AddressState>(
                  builder: (context, addrState) {
                    return Column(
                      children: [
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
                        20.sBHh,
                        _buildDropdown(
                          loading: addrState.isStateLoading == AppState.loading,
                          title: 'State',
                          controller: _stateController,
                          items: addrState.statesData?.data?.map((e) => e.name).toList() ?? [],
                        ),
                      ],
                    );
                  },
                ),
                20.sBHh,
                CustomFormFieldWidget(
                  title: 'Postal Code',
                  controller: _postalCodeController,
                  validator: (value) => AppValidator.validateRequired(_postalCodeController.text, 'Postal Code'),
                ),

                20.sBHh,
                Text("Address Proof", style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
                8.sBHh,
                FormField(
                  validator: (value) => AppValidator.validateRequired(_addressOfProof, "Address of Proof"),
                  builder: (field) => Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      UploadWidget(
                        imagePath: _addressOfProof,
                        onTap: () async {
                          final image = await _imagePicker.pickImage(source: ImageSource.gallery);
                          if (image != null) {
                            setState(() => _addressOfProof = image.path);
                            field.didChange(image.path);
                          }
                        },
                      ),
                      if (field.hasError)
                        Padding(
                          padding: const EdgeInsets.only(top: 4),
                          child: Text(field.errorText!, style: TextStyle(color: AppColor.error, fontSize: 12)),
                        ),
                    ],
                  ),
                ),
                30.sBHh,
                SizedBox(
                  width: double.infinity,
                  child: AppButton(onPressed: _onNext, title: 'Next'),
                ),
                20.sBHh,
              ],
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
}
