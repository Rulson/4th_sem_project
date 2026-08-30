import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/routes/app_router.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/custom_searchable_dropdown.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/send_money/widget/upload_widget.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_cubit.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_state.dart';
import 'package:remit_management/modules/sign_up/models/param/create_ac_param.dart';

class DocumentVerificationPage extends StatefulWidget {
  final String firstName;
  final String lastName;
  final String phoneNumber;
  final String dob;
  // final String countryOfBirth;
  final String gender;
  final String street;
  final String suburb;
  final String postalCode;
  final String stateAddress;
  final String addressOfProof;

  const DocumentVerificationPage({
    super.key,
    required this.firstName,
    required this.lastName,
    required this.phoneNumber,
    required this.dob,
    // required this.countryOfBirth,
    required this.gender,
    required this.street,
    required this.suburb,
    required this.postalCode,
    required this.stateAddress,
    required this.addressOfProof,
  });

  @override
  State<DocumentVerificationPage> createState() => _DocumentVerificationPageState();
}

class _DocumentVerificationPageState extends State<DocumentVerificationPage> {
  final _idTypeController = TextEditingController();
  final _idController = TextEditingController();
  final _expiryDateController = TextEditingController();
  final _issuedByController = TextEditingController();

  final _formKey = GlobalKey<FormState>();

  String? _idFrontImage;
  String? _idBackImage;

  final _imagePicker = ImagePicker();

  @override
  void dispose() {
    _idTypeController.dispose();
    _idController.dispose();
    _expiryDateController.dispose();
    _issuedByController.dispose();
    super.dispose();
  }

  void _onSubmit() {
    if (_formKey.currentState!.validate()) {
      final state = context.read<CreateAccountCubit>().state;
      context.read<CreateAccountCubit>().register(RegisterParam(
            firstName: widget.firstName,
            lastName: widget.lastName,
            phoneNumber: widget.phoneNumber,
            dob: widget.dob,
            countryListId: "13",
            street: widget.street,
            suburb: widget.suburb,
            postcode: widget.postalCode,
            state: widget.stateAddress,
            addressProof: widget.addressOfProof,
            idType: _idTypeController.text,
            idNumber: _idController.text,
            expiryDate: _expiryDateController.text,
            issuedBy: _issuedByController.text,
            image: _idFrontImage!,
            image1: _idBackImage!,
            password: state.password ?? "",
            passwordConfirmation: state.passwordConfrimation ?? "",
            email: state.email ?? "",
            otp: state.otp ?? "",
            invividualRemarks: "No remarks",
          ));
    }
  }

  @override
  Widget build(BuildContext context) {
    final cubitState = context.watch<CreateAccountCubit>().state;

    return BlocListener<CreateAccountCubit, CreateAccountState>(
      listener: (context, state) {
        if (state.registrationStatus == true && state.isRegisterLoading == AppState.success) {
          AppSnackbar.showSnackBar(
            context: currentGlobalContext ?? context,
            message: state.registerModel?.message ?? "Registration Successful",
            type: SnackBarType.success,
          );
          context.read<CreateAccountCubit>().resetRegisterLoading();
          Future.delayed(const Duration(milliseconds: 2), () {
            context.pushReplacement(AppRoutes.accountSetupSuccess);
          });
        } else if (state.registrationStatus == false && state.isRegisterLoading == AppState.error) {
          AppSnackbar.showSnackBar(
            context: currentGlobalContext ?? context,
            message: "Registration failed",
            type: SnackBarType.error,
          );
          context.read<CreateAccountCubit>().resetRegisterLoading();
        }
      },
      child: Scaffold(
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
                "3/3",
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
                              "Document Verification",
                              style: AppText.titleExtraLargeBold.copyWith(fontSize: 22, color: AppColor.black),
                            ),
                            Text(
                              "Enter your details as shown on your official ID",
                              style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
                            ),
                          ],
                        ),
                      ),
                      Image.asset(SAppAssets.imageSignup),
                    ],
                  ),
                  16.sBHh,
                  CustomSearchableDropdown(
                    controller: _idTypeController,
                    title: 'ID Type',
                    hint: 'Please select ID Type',
                    validator: (value) => AppValidator.validateRequired(_idTypeController.text, "Id Type"),
                    items: ["Passport", "Driving License", "Photo ID"],
                  ),
                  20.sBHh,
                  CustomFormFieldWidget(
                    title: 'ID Number',
                    controller: _idController,
                    validator: (value) => AppValidator.validateRequired(_idController.text, "ID Number"),
                  ),
                  20.sBHh,
                  CustomDateSelector(
                    validator: (value) => AppValidator.validateRequired(_expiryDateController.text, "Expiry Date"),
                    inputFormatters: [],
                    onTap: () async {
                      final picked = await showDatePicker(
                        context: context,
                        firstDate: DateTime(1950),
                        lastDate: DateTime(2150),
                      );
                      if (picked != null) {
                        setState(() {
                          _expiryDateController.text = DateFormat('MM/dd/yyyy').format(picked);
                        });
                      }
                    },
                    controller: _expiryDateController,
                    title: 'Expiry Date',
                    hint: '',
                    onChanged: (_) {},
                  ),
                  20.sBHh,
                  CustomFormFieldWidget(
                    title: 'Issued By',
                    controller: _issuedByController,
                    validator: (value) => AppValidator.validateRequired(_issuedByController.text, "Issued By"),
                  ),
                  20.sBHh,
                  Text("ID Front Image", style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
                  8.sBHh,
                  FormField(
                    validator: (value) => AppValidator.validateRequired(_idFrontImage, "ID Front Image"),
                    builder: (field) => Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        UploadWidget(
                          imagePath: _idFrontImage,
                          onTap: () async {
                            final image = await _imagePicker.pickImage(source: ImageSource.gallery);
                            if (image != null) {
                              setState(() => _idFrontImage = image.path);
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
                  20.sBHh,
                  Text("ID Back Image", style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
                  8.sBHh,
                  FormField(
                    validator: (value) => AppValidator.validateRequired(_idBackImage, "ID Back Image"),
                    builder: (field) => Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        UploadWidget(
                          imagePath: _idBackImage,
                          onTap: () async {
                            final image = await _imagePicker.pickImage(source: ImageSource.gallery);
                            if (image != null) {
                              setState(() => _idBackImage = image.path);
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
                    child: AppButton(
                      isLoading: cubitState.isRegisterLoading == AppState.loading,
                      onPressed: _onSubmit,
                      title: 'Submit',
                    ),
                  ),
                  20.sBHh,
                ],
              ),
            ),
          ),
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
