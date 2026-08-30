class AppValidator {
  AppValidator._();
  static String? validateEmail(value, {List<String>? existingEmails}) {
    String pattern = r"^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$";
    RegExp regex = RegExp(pattern);
    if (!regex.hasMatch(value) || value == null) {
      return 'Enter a valid email address';
    } else if ((existingEmails ?? []).where((email) => email == value).length > 1) {
      return 'This Email already exists in the list';
    }
    return null;
  }

  static String? validatePassword(value) {
    // String pattern =
    //     r'^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#\$&*~]).{8,}$';
    // RegExp regex = RegExp(pattern);
    if (value.isEmpty) {
      return 'Please enter password';
    } else {
      // Check individual conditions
      if (value.length < 7) {
        return 'Password must be at least 8 characters';
      }

      if (!RegExp(r'(?=.*?[A-Z])').hasMatch(value)) {
        return 'Password must contain at least one uppercase letter';
      }

      if (!RegExp(r'(?=.*?[a-z])').hasMatch(value)) {
        return 'Password must contain at least one lowercase letter';
      }

      if (!RegExp(r'(?=.*?[0-9])').hasMatch(value)) {
        return 'Password must contain at least one digit';
      }

      if (!RegExp(r'(?=.*?[!_@#\$&*~-])').hasMatch(value)) {
        return 'Password must contain at least one special character';
      }
    }
    return null;
  }

  static String? validatePasswordConfirmation(String? value, String password) {
    if (value != password) {
      return 'Passwords do not match';
    }
    return null;
  }

  static String? validateRequired(String? value, String fieldName) {
    if (value == null || value.isEmpty) {
      return '$fieldName is required';
    }
    return null;
  }

  static String? validatePhone(String? phone, String fieldName) {
    String pattern = r"^(?:\+?61|0)4([0-9]{8})$";
    RegExp regExp = RegExp(pattern);

    if (phone == null || !regExp.hasMatch(phone)) {
      return "Enter a valid phone number";
    }

    return null;
  }
}
