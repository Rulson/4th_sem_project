import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/custom_searchable_dropdown.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_cubit.dart';
import 'package:remit_management/modules/sign_up/bloc/stepper_cubit.dart';

class CreateAccountScreen extends StatelessWidget {
  const CreateAccountScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(create: (context) => StepperCubit()),
        BlocProvider(create: (context) => CreateAccountCubit()),
      ],
      child: CreateAccounotView(),
    );
  }
}

class CreateAccounotView extends StatefulWidget {
  const CreateAccounotView({super.key});

  @override
  State<CreateAccounotView> createState() => _CreateAccounotViewState();
}

class _CreateAccounotViewState extends State<CreateAccounotView> {
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _dobController = TextEditingController();
  final TextEditingController _country = TextEditingController();
  ////
  final TextEditingController _streetNoController = TextEditingController();
  final TextEditingController _suburbController = TextEditingController();
  final TextEditingController _postalCodeController = TextEditingController();
  final TextEditingController _stateController = TextEditingController();
  final TextEditingController _findCountry = TextEditingController();
  ////
  final TextEditingController _idTypeController = TextEditingController();
  final TextEditingController _idController = TextEditingController();
  final TextEditingController _expiryDateController = TextEditingController();
  final TextEditingController _issuedByController = TextEditingController();
  // final TextEditingController _findCountry = TextEditingController();

  String? _selectedGender;

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<StepperCubit, int>(
      builder: (context, stepperState) {
        return Scaffold(
          bottomNavigationBar: Padding(
            padding: const EdgeInsets.all(12.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                SizedBox(
                  width: 120,
                  child: AppButton(
                    customColor: AppColor.white,
                    textColor: AppColor.black,
                    onPressed: () {
                      if (stepperState > 1) {
                        context.read<StepperCubit>().decrement();
                      }
                    },
                    title: 'Back',
                  ),
                ),
                SizedBox(
                  width: 120,
                  child: AppButton(
                    onPressed: () {
                      if (stepperState < 3) {
                        context.read<StepperCubit>().increment();
                      }
                      if (stepperState == 3) {
                        context.push(AppRoutes.accountSetupSuccess);
                      }
                    },
                    title: 'Next',
                  ),
                ),
              ],
            ),
          ),
          body: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SizedBox(height: Utils.statusBarHeight(context)),
                Column(
                  children: [
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 10),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          GestureDetector(
                            onTap: () {
                              context.pop();
                            },
                            child: Padding(
                              padding: const EdgeInsets.all(16.0),
                              child: SvgPicture.asset(height: 20, width: 20, SAppAssets.iconArrowBack),
                            ),
                          ),
                          Text("Welcome to RemitSystemm", style: TextStyle(fontSize: 18, fontWeight: FontWeight.w500)),
                          Text("Step $stepperState/3", style: TextStyle(fontSize: 14, color: AppColor.grey)),
                        ],
                      ),
                    ),
                    Row(
                      children: [
                        Expanded(child: Container(height: 3, color: AppColor.redII)),
                        Expanded(child: Container(height: 3, color: stepperState > 1 ? AppColor.redII : AppColor.grey)),
                        Expanded(child: Container(height: 3, color: stepperState > 2 ? AppColor.redII : AppColor.grey)),
                      ],
                    ),
                  ],
                ),
                20.sBHh,
                AnimatedCrossFade(
                  duration: const Duration(milliseconds: 400),
                  crossFadeState: stepperState == 1 ? CrossFadeState.showFirst : CrossFadeState.showSecond,
                  firstChild: _tellUsAbout(),
                  secondChild: stepperState == 2 ? _findYou() : _documentVerification(),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  //   Widget _buildStepperChild(int state) {
  //   switch (state) {
  //     case 1:
  //       return _tellUsAbout();
  //     case 2:
  //       return _findYou();
  //     case 3:
  //       return _documentVerification();
  //     default:
  //       return SizedBox.shrink();
  //   }
  // }

  Padding _documentVerification() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        spacing: 20,
        children: [
          Text(
            "Document Verification",
            style: TextStyle(color: AppColor.primary, fontSize: 18, fontWeight: FontWeight.w500),
          ),
          CustomSearchableDropdown(
            controller: _idTypeController,
            title: 'ID Type',
            hint: 'Please select ID Type',
            validator: (value) {
              return null;
            },
            items: [],
          ),
          CustomFormFieldWidget(title: 'ID Number', controller: _idController),
          CustomDateSelector(
            validator: (p1) {
              return null;
            },
            inputFormatters: [],
            onTap: () {},
            controller: _expiryDateController,
            title: 'Expiry Date',
            hint: '',
            onChanged: (value) {},
          ),
          CustomFormFieldWidget(title: 'Issued By', controller: _issuedByController),
          Text("Address of proof", style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
          // 5.sBHh,
          Image.asset(SAppAssets.imageInFrontFrame),
          Image.asset(SAppAssets.imageInBackFrame),
          10.sBHh,
        ],
      ),
    );
  }

  Padding _findYou() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        spacing: 20,
        children: [
          Text(
            "Where Can We Find You?",
            style: TextStyle(color: AppColor.primary, fontSize: 18, fontWeight: FontWeight.w500),
          ),
          CustomFormFieldWidget(title: 'Unit/Street No.', controller: _streetNoController),
          CustomFormFieldWidget(title: 'Suburb', controller: _suburbController),
          CustomFormFieldWidget(title: 'Postal Code', controller: _postalCodeController),
          CustomFormFieldWidget(title: 'State', controller: _stateController),
          CustomFormFieldWidget(title: 'Country', controller: _findCountry),
          Text("Address of proof", style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
          // 5.sBHh,
          Image.asset(SAppAssets.imagePhotoFrame),
          10.sBHh,
        ],
      ),
    );
  }

  Padding _tellUsAbout() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        spacing: 20,
        children: [
          Text(
            "Tell Us About Yourself",
            style: TextStyle(color: AppColor.primary, fontSize: 18, fontWeight: FontWeight.w500),
          ),
          CustomFormFieldWidget(title: 'First Name', controller: _firstNameController),
          CustomFormFieldWidget(title: 'Last Name', controller: _lastNameController),
          CustomDateSelector(
            validator: (p1) {
              return null;
            },
            inputFormatters: [],
            onTap: () {},
            controller: _dobController,
            title: 'Date of Birth',
            hint: 'MM/DD/YYYY',
            onChanged: (value) {},
          ),
          CustomSearchableDropdown(
            controller: _country,
            title: 'Country of Birth',
            hint: 'Select an option',
            validator: (value) {
              return null;
            },
            items: [],
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Gender', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
              5.sBHh,
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  _buildGenderOption('Male'),
                  SizedBox(width: 10),
                  _buildGenderOption('Female'),
                  SizedBox(width: 10),
                  _buildGenderOption('Other'),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildGenderOption(String gender) {
    return GestureDetector(
      onTap: () {
        setState(() {
          _selectedGender = gender;
        });
      },
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey.shade400),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          children: [
            Text(gender, style: TextStyle(fontSize: 16)),
            SizedBox(width: 8),
            Container(
              width: 20,
              height: 20,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                border: Border.all(color: Colors.black, width: 2),
              ),
              child: _selectedGender == gender
                  ? Center(
                      child: Container(
                        width: 10,
                        height: 10,
                        decoration: BoxDecoration(shape: BoxShape.circle, color: Colors.black),
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
  final String? Function(String? p1)? validator;
  final List<TextInputFormatter>? inputFormatters;
  final Function()? onTap;
  final void Function(String p1)? onChanged;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
        5.sBHh,
        Container(
          decoration: BoxDecoration(
            color: Colors.white,
            border: Border.all(color: Colors.grey.shade300),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Padding(
            padding: const EdgeInsets.all(8.0),
            child: TextFormField(
              onChanged: onChanged,
              onTap: onTap,
              controller: controller,
              decoration: InputDecoration(
                floatingLabelBehavior: hint != "" ? FloatingLabelBehavior.always : FloatingLabelBehavior.auto,
                focusedBorder: InputBorder.none,
                enabledBorder: InputBorder.none,
                errorBorder: InputBorder.none,
                disabledBorder: InputBorder.none,
                // labelText: label,
                hintText: hint,
                border: InputBorder.none,
                counterText: '',
                labelStyle: Theme.of(context).textTheme.titleLarge?.copyWith(
                  fontSize: 18,
                  fontWeight: FontWeight.w500,
                  color: AppColor.labelTextColor,
                ),
                hintStyle: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: AppColor.hintTextColor,
                  fontSize: 14,
                  fontWeight: FontWeight.w500,
                ),
                contentPadding: const EdgeInsets.symmetric(horizontal: 10),
              ),
              // maxLength: maxLength,
              inputFormatters: inputFormatters,
              validator: validator,
              textCapitalization: TextCapitalization.sentences,
            ),
          ),
        ),
      ],
    );
  }
}
