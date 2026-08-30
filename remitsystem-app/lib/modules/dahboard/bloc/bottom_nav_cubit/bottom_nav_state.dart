import 'package:equatable/equatable.dart';

class BottomNavState extends Equatable {
  final int bottomIndex;
  const BottomNavState({required this.bottomIndex});

  BottomNavState copyWith({int? bottomIndex}) =>
      BottomNavState(bottomIndex: bottomIndex ?? this.bottomIndex);

  factory BottomNavState.initial() => BottomNavState(bottomIndex: 0);
  @override
  List<Object?> get props => [bottomIndex];
}
