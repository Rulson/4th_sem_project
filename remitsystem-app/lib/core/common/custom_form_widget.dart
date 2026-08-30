import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';



class CustomFormFieldWidget extends StatelessWidget {
  final TextEditingController? controller;
  final String? label;
  final String? hint;
  final String? Function(String?)? validator;
  final TextInputType? keyboardType;
  final List<TextInputFormatter>? inputFormatters;
  final BorderRadiusGeometry? borderRadius;
  final bool readOnly;
  final bool showBoarder;
  final bool hideParentBorder;
  final String? title;
  final Function(String)? onChanged;
  final Widget? suffixIcon;
  final IconData? prefixIcon;
  final bool? obsecureText;
  final TextStyle? textStyle;
  final TextStyle? hintStyle;

  final Color? prefixIconColor;
  final String? errorText;

  const CustomFormFieldWidget(
      {super.key,
      this.controller,
      this.title,
      this.label,
      this.hint,
      this.validator,
      this.keyboardType,
      this.inputFormatters,
      this.borderRadius,
      this.readOnly = false,
      this.showBoarder = true,
      this.onChanged,
      this.hideParentBorder = false,
      this.suffixIcon,
      this.prefixIcon,
      this.obsecureText,
      this.textStyle,
      this.hintStyle,
      this.prefixIconColor,
      this.errorText});

  @override
  Widget build(BuildContext context) {
    return FormField<String>(
      validator: validator,
      autovalidateMode: AutovalidateMode.onUserInteraction,
      builder: (field) {
        final String? finalError = errorText ?? field.errorText;

        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (title != null)
              Text(
                title!,
                style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w500,
                ),
              ),
            5.sBHh,
            Container(
              decoration: BoxDecoration(
                color: Colors.white,
                border: hideParentBorder
                    ? null
                    : Border.all(
                        color: finalError!=null ? Colors.red : Colors.grey.shade300,
                      ),
                borderRadius: borderRadius ?? BorderRadius.circular(6),
              ),
              child: TextFormField(
                style: textStyle,
                onTapOutside: (_) {
                  FocusManager.instance.primaryFocus?.unfocus();
                },
                controller: controller,
                obscureText: obsecureText ?? false,
                keyboardType: keyboardType,
                readOnly: readOnly,
                inputFormatters: inputFormatters,
                textCapitalization: TextCapitalization.sentences,
                onChanged: (value) {
                  field.didChange(value);
                  onChanged?.call(value);
                },
                decoration: InputDecoration(
                  hintText: hint,
                  hintStyle: hintStyle ?? AppText.textFieldLarge.copyWith(color: AppColor.textSecondary),
                  prefixIcon: prefixIcon != null ? Icon(prefixIcon, size: 24, color: prefixIconColor ?? AppColor.textSecondary) : null,
                  suffixIcon: suffixIcon,
                  border: InputBorder.none,
                  contentPadding: const EdgeInsets.all(18),
                ),
              ),
            ),
            if (finalError != null) ...[
              6.sBHh,
              Text(
                finalError,
                style: const TextStyle(
                  color: Colors.red,
                  fontSize: 12,
                ),
              ),
            ],
          ],
        );
      },
    );
  }
}
