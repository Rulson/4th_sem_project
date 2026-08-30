import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:intl/intl.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_cubit.dart';
import 'package:remit_management/modules/sign_up/screens/find_you_page.dart';

class AccountSetupScreen extends StatefulWidget {
  const AccountSetupScreen({super.key});

  @override
  State<AccountSetupScreen> createState() => _AccountSetupScreenState();
}

class _AccountSetupScreenState extends State<AccountSetupScreen> {
  final _firstNameController = TextEditingController();
  final _lastNameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _dobController = TextEditingController();

  final _formKey = GlobalKey<FormState>();

  String? _selectedGender;

  @override
  void initState() {
    super.initState();
    if (kDebugMode) {
      _firstNameController.text = "abcnskle";
      _lastNameController.text = "sjfoksadl";
      _phoneController.text = '61423565212';
    }
  }

  @override
  void dispose() {
    _firstNameController.dispose();
    _lastNameController.dispose();
    _phoneController.dispose();
    _dobController.dispose();
    super.dispose();
  }

  void _onNext() {
    if (_formKey.currentState!.validate()) {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (_) => FindYouPage(
            firstName: _firstNameController.text,
            lastName: _lastNameController.text,
            phoneNumber: _phoneController.text,
            dob: _dobController.text,
            // countryOfBirth: _countryController.text,
            gender: _selectedGender ?? '',
          ),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.watch<CreateAccountCubit>().state;
    return Scaffold(
      backgroundColor: AppColor.white,
      
      appBar: AppBar(
        actions: [ Padding(
            padding: EdgeInsets.only(right: 22.w),
            child: Text(
              "1/3",
              style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
            ),
          ),],
        backgroundColor: AppColor.white,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => Navigator.pop(context),
        ),
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
                            "Tell Us About Yourself",
                            style: AppText.titleExtraLargeBold
                                .copyWith(fontSize: 22, color: AppColor.black),
                          ),
                          Text(
                            "Enter your details as shown on your official ID",
                            style: AppText.bodyMedium400
                                .copyWith(color: AppColor.textBody),
                          ),
                        ],
                      ),
                    ),
                    Image.asset(SAppAssets.imageSignup),
                  ],
                ),
                16.sBHh,
                CustomFormFieldWidget(
                  title: 'First Name',
                  controller: _firstNameController,
                  validator: (value) => AppValidator.validateRequired(
                      _firstNameController.text, 'First Name'),
                ),
                20.sBHh,
                CustomFormFieldWidget(
                  title: 'Last Name',
                  controller: _lastNameController,
                  validator: (value) => AppValidator.validateRequired(
                      _lastNameController.text, 'Last Name'),
                ),
                20.sBHh,
                CustomFormFieldWidget(
                  title: 'Phone Number',
                  controller: _phoneController,
                  validator: (value) => AppValidator.validatePhone(
                      _phoneController.text, 'Phone Number'),
                  keyboardType: const TextInputType.numberWithOptions(),
                ),
                20.sBHh,
                CustomDateSelector(
                  validator: (value) => AppValidator.validateRequired(
                      _dobController.text, 'Date of Birth'),
                  inputFormatters: [],
                  onTap: () async {
                    final picked = await showDatePicker(
                      context: context,
                      firstDate: DateTime(1950),
                      lastDate: DateTime.now(),
                    );
                    if (picked != null) {
                      setState(() {
                        _dobController.text =
                            DateFormat('MM/dd/yyyy').format(picked);
                      });
                    }
                  },
                  controller: _dobController,
                  title: 'Date of Birth',
                  hint: 'MM/DD/YYYY',
                  onChanged: (_) {},
                ),
                20.sBHh,
                // CustomSearchableDropdown(
                //   controller: _countryController,
                //   title: 'Country of Birth',
                //   hint: 'Select an option',
                //   validator: (value) => AppValidator.validateRequired(
                //       _countryController.text, 'Country of Birth'),
                //   items: ["Nepal", "Australia"],
                // ),
                // 20.sBHh,
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Gender',
                        style: TextStyle(
                            fontSize: 16, fontWeight: FontWeight.w500)),
                    5.sBHh,
                    FormField<String>(
                      initialValue: _selectedGender,
                      validator: (value) =>
                          AppValidator.validateRequired(value, "Gender"),
                      builder: (field) => Column(
                        children: [
                          Row(
                            children: [
                              Expanded(
                                  child: _buildGenderOption('Male', field)),
                              SizedBox(width: 10),
                              Expanded(
                                  child: _buildGenderOption('Female', field)),
                              SizedBox(width: 10),
                              Expanded(
                                  child: _buildGenderOption('Other', field)),
                            ],
                          ),
                          if (field.hasError)
                            Padding(
                              padding: const EdgeInsets.only(top: 4),
                              child: Text(field.errorText!,
                                  style: TextStyle(
                                      color: AppColor.error, fontSize: 12)),
                            ),
                        ],
                      ),
                    ),
                  ],
                ),
                30.sBHh,
                SizedBox(
                  width: double.infinity,
                  child: AppButton(
                    isLoading: cubit.isRegisterLoading == AppState.loading,
                    onPressed: _onNext,
                    title: 'Next',
                  ),
                ),
                20.sBHh,
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildGenderOption(String gender, FormFieldState<String> field) {
    final isSelected = _selectedGender == gender;
    return GestureDetector(
      onTap: () {
        setState(() => _selectedGender = gender);
        field.didChange(gender);
      },
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 12, vertical: 12),
        decoration: BoxDecoration(
          border: Border.all(
            color: isSelected ? AppColor.black : AppColor.gray100,
            width: isSelected ? 2 : 1,
          ),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(gender, style: TextStyle(fontSize: 14)),
            SizedBox(width: 6),
            Container(
              width: 20,
              height: 20,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                border: Border.all(color: AppColor.black, width: 2),
              ),
              child: isSelected
                  ? Center(
                      child: Container(
                        width: 10,
                        height: 10,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: AppColor.black,
                        ),
                      ),
                    )
                  : null,
            ),
          ],
        ),
      ),
    );
  }
}

class CustomDateSelector extends StatelessWidget {
  const CustomDateSelector({
    super.key,
    required this.controller,
    required this.title,
    required this.hint,
    required this.validator,
    required this.inputFormatters,
    required this.onTap,
    required this.onChanged,
  });

  final TextEditingController controller;
  final String title;
  final String hint;
  final String? Function(String?)? validator;
  final List<TextInputFormatter>? inputFormatters;
  final VoidCallback? onTap;
  final void Function(String)? onChanged;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: AbsorbPointer(
        child: CustomFormFieldWidget(
          controller: controller,
          title: title,
          hint: hint,
          validator: validator,
          inputFormatters: inputFormatters,
          onChanged: onChanged,
          readOnly: true,
          suffixIcon: Padding(
            padding: const EdgeInsets.only(right: 14),
            child: Icon(
              Icons.calendar_today_outlined,
              size: 18,
              color: AppColor.textSecondary,
            ),
          ),
        ),
      ),
    );
  }
}
