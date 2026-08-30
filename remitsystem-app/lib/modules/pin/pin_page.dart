import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_string_const.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_loader_indicator.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/pin/bloc/pin_cubit.dart';
import 'package:remit_management/modules/pin/bloc/pin_state.dart';
import 'package:remit_management/modules/pin/model/pin_pm.dart';

class OtpPage extends StatelessWidget {
  final String pageType;
  const OtpPage({super.key, required this.pageType});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => PinCubit(),
      child: OtpPinView(
        pageType: pageType,
      ),
    );
  }
}

class OtpPinView extends StatefulWidget {
  final String pageType;

  const OtpPinView({super.key, required this.pageType});

  @override
  OtpPinViewState createState() => OtpPinViewState();
}

class OtpPinViewState extends State<OtpPinView> {
  // late BuildContext _context;
  final List _pin = [];
  // final String _otp_pin = "";

  @override
  void initState() {
    super.initState();
    debugPrint("Page type ${widget.pageType}");
  }

  _handlePress(i) {
    if (_pin.length == 3) {
      // setState(() {
      _pin.add(i);
      // });
      _handleSubmitLogin();
      // widget.set ? _handleSubmitSet() : _handleSubmitLogin();
    } else if (_pin.length == 4) {
      // _handleSubmitLogin();
      // debugPrint(_pin.length);
      //   Utilities.removeStackActivity(context, LoginScreen());
    } else {
      setState(() {
        _pin.add(i);
      });
    }
  }

  _handleDelete() {
    if (_pin.isEmpty) {
      // Utilities.toast("Nothing to remove", _context);
    } else {
      setState(() {
        _pin.removeLast();
      });
    }
  }

  _handleReset() {
    // Utilities.removeStackActivity(context, LoginScreen());
  }

  // _handleSubmitSet() {
  //   if (_pin.length == 4) {
  //     String a = _pin.join();
  //     // Utilities.setPreferences("otp_pin", a);
  //     // Utilities.removeStackActivity(context, BttnNavbar());
  //   } else {
  //     // Utilities.toast("Enter PIN", _context);
  //   }
  // }

  _handleSubmitLogin() {
    if (_pin.length == 4) {
      String a = _pin.join();
      if (widget.pageType == AppStringConst.setPin) {
        context.read<PinCubit>().setPin(PinPm(pin: int.tryParse(a) ?? 0));
      } else {
        context.read<PinCubit>().validatePin(PinPm(pin: int.tryParse(a) ?? 0));
      }
    }
  }

  Widget _getRow() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: List.generate(4, (index) {
        bool filled = index < _pin.length;
        return Padding(
          padding: const EdgeInsets.symmetric(horizontal: 12), // Increased space between boxes
          child: Container(
            width: 56,
            height: 56,
            decoration: BoxDecoration(
              color: const Color(0xFFF8FAFC), // gray 50
              borderRadius: BorderRadius.circular(12),
              border: Border.all(
                color: const Color(0xFFD1D5DB), // gray 300
                width: 2,
              ),
            ),
            child: Center(
              child: filled
                  ? Text(
                      '•',
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.black,
                      ),
                    )
                  : Container(),
            ),
          ),
        );
      }),
    );
  }

  @override
  Widget build(BuildContext context) {
    // _context = context;
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        leading: IconButton(
            onPressed: () {
              if (context.canPop()) {
                context.pop();
              } else {
                context.go(AppRoutes.signin);
              }
            },
            icon: Icon(
              Icons.arrow_back_ios_new,
              size: 20,
            )),
      ),
      body: BlocConsumer<PinCubit, PinState>(
        listener: (context, state) {
          if (state.isLoading == AppState.success) {
            if (widget.pageType == AppStringConst.setPin) {
              context.pushReplacement("${AppRoutes.otpPage}/${AppStringConst.validatePin}");
              // OtpPage(
              //   pageType: AppStringConst.validatePin,
              // )
              // context.read<SignInCubit>().resetState();
            } else {
              context.go(AppRoutes.dashboard);
            }
            if (!context.mounted) return;
            AppSnackbar.showSnackBar(context: context, message: state.message, type: SnackBarType.success);
          } else if (state.isLoading == AppState.error) {
            if (!context.mounted) return;
            _pin.clear();
            context.read<PinCubit>().resetState();
            AppSnackbar.showSnackBar(context: context, message: state.message, type: SnackBarType.error);
          }
        },
        builder: (context, state) {
          return SingleChildScrollView(
            child: SizedBox(
              height: Utils.screenHeight(context),
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                child: state.isLoading == AppState.loading
                    ? AppLoaderIndicator()
                    : Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                        children: <Widget>[
                          widget.pageType == AppStringConst.setPin
                              ? Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      "Set Pin",
                                      style: TextStyle(fontWeight: FontWeight.bold, fontSize: 32),
                                    ),
                                    // 20.sBHh,
                                    Text("After 10 min of inactivity, the app will lock. To unlock enter PIN or login again"),
                                    Text(
                                      "Choose a new PIN",
                                      style: TextStyle(fontWeight: FontWeight.w500),
                                    ),
                                  ],
                                )
                              : Text(
                                  "Enter Your Pin",
                                  style: AppText.titleExtraLargeBold.copyWith(color: AppColor.gray800),
                                ),
                          // 20.sBHh,
                          Flexible(
                            flex: 1,
                            child: Padding(
                              padding: const EdgeInsets.all(8.0),
                              child: _getRow(),
                            ),
                          ),
                          Flexible(
                            flex: 3,
                            child: SizedBox(
                              height: MediaQuery.of(context).size.height / 2,
                              child: GridView.count(
                                //                physics: NeverScrollableScrollPhysics(),
                                crossAxisCount: 3,
                                childAspectRatio: 2,
                                reverse: false,
                                children: <Widget>[
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(1);
                                      },
                                      child: Center(
                                        child: Text(
                                          (1).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(2);
                                      },
                                      child: Center(
                                        child: Text(
                                          (2).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(3);
                                      },
                                      child: Center(
                                        child: Text(
                                          (3).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(4);
                                      },
                                      child: Center(
                                        child: Text(
                                          (4).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(5);
                                      },
                                      child: Center(
                                        child: Text(
                                          (5).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(6);
                                      },
                                      child: Center(
                                        child: Text(
                                          (6).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(7);
                                      },
                                      child: Center(
                                        child: Text(
                                          (7).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(8);
                                      },
                                      child: Center(
                                        child: Text(
                                          (8).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(9);
                                      },
                                      child: Center(
                                        child: Text(
                                          (9).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  InkWell(
                                    splashColor: Colors.transparent,
                                    onTap: () {
                                      _handleReset();
                                      context.pop();
                                    },
                                    child: Center(
                                      child: Text("Cancel"),
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.all(8.0),
                                    child: InkWell(
                                      splashColor: Colors.transparent,
                                      onTap: () {
                                        _handlePress(0);
                                      },
                                      child: Center(
                                        child: Text(
                                          (0).toString(),
                                          style: TextStyle(
                                            fontSize: 20,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  GestureDetector(
                                    onTap: () {
                                      _handleDelete();
                                    },
                                    child: Center(
                                      // child: Icon(Icons.backspace),
                                      child: Text(
                                        "Delete",
                                      ),
                                    ),
                                  )
                                ],
                              ),
                            ),
                          ),
                        ],
                      ),
              ),
            ),
          );
        },
      ),
    );
  }
}

//List<Widget>.generate(12, (index) {
//                  if(index==11){
//                    return GestureDetector(
//                      onTap: (){
//                        _handleDelete();
//                      },
//                      child: new Center(
//                        child:Icon(Icons.backspace),
//                      ),
//                    );
//                  }
//                  if(index==10){
//                    return InkWell(
//                      onTap: (){
//                        _handleReset();
//                      },
//                      child: new Center(
//                        child:Text("Forgot".toUpperCase()),
//                      ),
//                    );
//                  }
//                  return Padding(
//                    padding: const EdgeInsets.all(8.0),
//                    child: InkWell(
//                      onTap: (){
//                        _handlePress(9-index);
//                      },
//                    child: new Center(
//                      child: new Text((9-index).toString(),style: TextStyle(
//                        fontSize: 20,
//                      ),),
//                    ),
//                    ),
//                  );
//                })
