import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/resource/app_colors.dart';

class AppBottomButton extends StatelessWidget {
  final bool disableNextButton;
  final double padding;
  final void Function() onNextTap;
  const AppBottomButton({
    super.key,
    this.disableNextButton = false,
    this.padding = 16.0,
    required this.onNextTap,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.all(padding),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          SizedBox(
            width: 120,
            child: AppButton(
                customColor: AppColor.white,
                textColor: AppColor.black,
                customBorder: Border.all(color: AppColor.gray300),
                borderRadius: 999,
                onPressed: () {
                  context.pop();
                },
                title: 'Back'),
          ),
          SizedBox(
              width: 120,
              child: AppButton(
                  borderRadius: 999,
                  isDisabled: disableNextButton,
                  onPressed: onNextTap,
                  title: 'Next')),
        ],
      ),
    );
  }
}
