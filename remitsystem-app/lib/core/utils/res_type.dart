import 'package:dartz/dartz.dart';
import 'package:remit_management/core/common/models/res_model.dart';

typedef AppResponse<T> = Future<Either<String, ResModel<T>>>;
