import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:multi_image_picker_view/multi_image_picker_view.dart';

class Utils {
  Utils._();
  static void cPrint(String value) {
    log(value);
  }

  static double screenWidth(BuildContext context) {
    double screenWidth = MediaQuery.sizeOf(context).width;
    return screenWidth;
  }

  static double screenHeight(BuildContext context) {
    double screenHeight = MediaQuery.sizeOf(context).height;
    return screenHeight;
  }

  static double statusBarHeight(BuildContext context) {
    double statusBarHeight = MediaQuery.of(context).padding.top;
    return statusBarHeight;
  }

  static String hideText(String email) {
    int atIndex = email.indexOf('@');
    if (atIndex <= 2) return email; // If email is too short, return as is.

    String start = email.substring(0, 2); // First 2 characters
    String end = email.substring(atIndex); // Domain part
    String masked = '*' * (atIndex - 2); // Mask rest of the email

    return '$start$masked$end';
  }

  static String formatDuration(int seconds) {
    if (seconds < 60) {
      return '${seconds}s';
    } else {
      int minutes = seconds ~/ 60;
      int remainingSeconds = seconds % 60;

      if (remainingSeconds == 0) {
        return '${minutes}min';
      } else {
        return '$minutes:${remainingSeconds}s';
      }
    }
  }

  static String getAbbreviation(String? input) {
    if (input == null || input.isEmpty) return "";

    List<String> words = input.trim().split(RegExp(r'\s+'));
    if (words.length > 1) {
      return "${words[0][0]}${words[words.length - 1][0]}".toUpperCase();
    } else {
      return words[0][0].toUpperCase();
    }
  }

  static String formatDouble(double input, {int places = 2}) {
    return input.toStringAsFixed(places);
  }

  static Future<List<ImageFile>> pickImagesUsingImagePicker(bool allowMultiple) async {
    final picker = ImagePicker();
    final List<XFile> xFiles;
    if (allowMultiple) {
      xFiles = await picker.pickMultiImage(maxWidth: 1080, maxHeight: 1080);
    } else {
      xFiles = [];
      final xFile = await picker.pickImage(source: ImageSource.gallery, maxHeight: 1080, maxWidth: 1080);
      if (xFile != null) {
        xFiles.add(xFile);
      }
    }
    if (xFiles.isNotEmpty) {
      return xFiles.map<ImageFile>((e) => convertXFileToImageFile(e)).toList();
    }
    return [];
  }

  static String convertAudToNpr({required String aud, required String todayRate}) {
    return (double.parse(aud) * double.parse(todayRate)).toStringAsFixed(2);
  }

  static String convertNprToAud({required String aud, required String todayRate}) {
    return (double.parse(aud) / double.parse(todayRate)).toStringAsFixed(2);
  }

  static String formatExpiryDate(String? rawDate) {
  if (rawDate == null || rawDate.isEmpty) return "N/A";
  try {
    final date = DateTime.parse(rawDate);
    return DateFormat('MMM dd, yyyy').format(date); // → Jan 01, 2030
  } catch (e) {
    return "N/A";
  }
}

  static String? parseString(dynamic value) {
    if (value == null) return null;
    if (value is String) return value;
    if (value is int || value is double || value is bool) {
      return value.toString();
    }
    return null;
  }
  static String formatTimeAgo(String createdAtStr) {
    DateTime createdAt = DateTime.parse(createdAtStr);
    DateTime now = DateTime.now();
    Duration diff = now.toLocal().difference(createdAt.toLocal());

    if (diff.inDays > 365) return "${(diff.inDays / 365).floor()}y ago";
    if (diff.inDays > 30) return "${(diff.inDays / 30).floor()}mo ago";
    if (diff.inDays > 0) return "${diff.inDays}d ago";
    if (diff.inHours > 0) return "${diff.inHours}h ago";
    if (diff.inMinutes > 0) return "${diff.inMinutes}m ago";
    return "just now";
  }
}

String getDateName(String dateStr, {required bool label}) {
  final DateFormat format = DateFormat("dd-MMM-yyyy");

  final DateTime date = format.parse(dateStr);

  if (!label) return DateFormat("dd MMM yyyy").format(date);

  final now = DateTime.now();
  final diff = now.difference(date);

  if (_isSameDay(date, now)) {
    return "Today";
  }

  if (_isSameDay(date, now.subtract(const Duration(days: 1)))) {
    return "Yesterday";
  }

  if (diff.inDays <= 7) {
    return "This Week";
  }

  if (date.year == now.year) {
    return DateFormat.MMMM().format(date);
  }

  return date.year.toString();
}

bool _isSameDay(DateTime a, DateTime b) {
  return a.year == b.year && a.month == b.month && a.day == b.day;
}

String getFormattedTime(String dateStr) {
  if (dateStr.isEmpty) return "--:--";

  final List<DateFormat> inputFormats = [
    DateFormat("dd-MMM-yyyy HH:mm:s"),
    DateFormat("yyyy-MM-dd HH:mm:ss"),
    DateFormat("yyyy-MM-dd HH:mm:ssSSSS"),
  ];

  final outputFormat = DateFormat("hh:mm a");

  for (var format in inputFormats) {
    try {
      final dateTime = format.parse(dateStr);
      return outputFormat.format(dateTime);
    } catch (_) {
      continue;
    }
  }

  try {
    final dateTime = DateTime.parse(dateStr);
    return outputFormat.format(dateTime);
  } catch (e) {
    return "Invalid Time";
  }
}

String getFormattedDate(String dateStr) {
  if (dateStr.isEmpty) return "--/--/----";

  final List<DateFormat> inputFormats = [
    DateFormat("yyyy-MM-dd HH:mm:ssSSSS"),
    DateFormat("yyyy-MM-dd HH:mm:ss"),
    DateFormat("dd-MMM-yyyy HH:mm:s"),
    DateFormat("yyyy-MM-dd"),
  ];

  final outputFormat = DateFormat("yyyy-MM-dd");

  for (var format in inputFormats) {
    try {
      final dateTime = format.parse(dateStr);
      return outputFormat.format(dateTime);
    } catch (_) {
      continue;
    }
  }

  try {
    final dateTime = DateTime.parse(dateStr);
    return outputFormat.format(dateTime);
  } catch (e) {
    return "Invalid Date";
  }
}