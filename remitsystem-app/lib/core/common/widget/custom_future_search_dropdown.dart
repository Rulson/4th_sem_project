import 'dart:async';
import 'package:flutter/material.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/utils/app_loader_indicator.dart';

class CustomFutureSearchDropdown extends StatefulWidget {
  final TextEditingController controller;
  final Future<List<String>> Function(String query) fetchItems;
  final String hint;
  final String title;
  final void Function(String?)? onChanged;
  final String? Function(String?)? validator;
  final bool disableDropdown;
  // final int debounceMilliseconds;

  const CustomFutureSearchDropdown({
    super.key,
    required this.controller,
    required this.fetchItems,
    required this.hint,
    required this.title,
    this.onChanged,
    this.validator,
    this.disableDropdown = false,
    // this.debounceMilliseconds = 500,
  });

  @override
  State<CustomFutureSearchDropdown> createState() => _CustomFutureSearchDropdownState();
}

class _CustomFutureSearchDropdownState extends State<CustomFutureSearchDropdown> {
  final LayerLink _layerLink = LayerLink();
  OverlayEntry? _overlayEntry;
  final FocusNode _focusNode = FocusNode();
  String? _hoveredItem;
  List<String> _items = [];
  bool _isLoading = false;
  bool _overlayVisible = false;
  Timer? _closeTimer;

  @override
  void initState() {
    super.initState();
    widget.controller.addListener(_onSearchChanged);
  }

  @override
  void dispose() {
    _closeTimer?.cancel();
    _focusNode.dispose();
    widget.controller.removeListener(_onSearchChanged);
    super.dispose();
  }

  void _onSearchChanged() {
    if (!_overlayVisible) {
      _showOverlay();
    }
    if (widget.controller.text.trim().isNotEmpty) {
      setState(() => _isLoading = true);
      _overlayEntry?.markNeedsBuild();
    }
    _performSearch();
  }

  Future<void> _performSearch() async {
    final query = widget.controller.text.trim();
    if (query.isEmpty) {
      if (mounted) {
        setState(() {
          _items = [];
          _isLoading = false;
        });
        _overlayEntry?.markNeedsBuild();
      }
      return;
    }
    if (mounted) {
      setState(() => _isLoading = true);
      _overlayEntry?.markNeedsBuild();
    }
    try {
      final results = await widget.fetchItems(query);
      if (mounted) {
        setState(() {
          _items = results;
          _isLoading = false;
        });
        _overlayEntry?.markNeedsBuild();
      }
    } catch (_) {
      if (mounted) {
        setState(() {
          _items = [];
          _isLoading = false;
        });
        _overlayEntry?.markNeedsBuild();
      }
    }
  }

  void _showOverlay() {
    if (widget.disableDropdown) return;
    _closeTimer?.cancel();
    _overlayVisible = true;
    _removeOverlay();

    final query = widget.controller.text.trim();
    if (query.isNotEmpty) {
      _performSearch();
    }

    final RenderBox renderBox = context.findRenderObject() as RenderBox;
    final Size size = renderBox.size;

    _overlayEntry = OverlayEntry(
      builder: (context) => Positioned(
        width: size.width,
        child: CompositedTransformFollower(
          link: _layerLink,
          showWhenUnlinked: false,
          offset: Offset(0.0, size.height - 16),
          child: Material(
            elevation: 8,
            borderRadius: BorderRadius.circular(12),
            child: Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: Colors.grey.shade200),
              ),
              constraints: const BoxConstraints(maxHeight: 250),
              child: _isLoading
                  ? const Center(child: AppLoaderIndicator())
                  : _items.isEmpty
                      ? Center(
                          child: Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Text(
                              'No items found',
                              style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
                            ),
                          ),
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.only(top: 6, bottom: 20),
                          shrinkWrap: true,
                          itemCount: _items.length,
                          itemBuilder: (context, index) {
                            final item = _items[index];
                            final isHovered = _hoveredItem == item;
                            return MouseRegion(
                              onEnter: (_) => setState(() => _hoveredItem = item),
                              onExit: (_) => setState(() => _hoveredItem = null),
                              child: InkWell(
                                onTap: () {
                                  widget.controller.text = item;
                                  widget.onChanged?.call(item);
                                  _removeOverlay();
                                },
                                child: Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                                  decoration: BoxDecoration(
                                    color: isHovered ? Colors.grey.shade100 : Colors.transparent,
                                  ),
                                  child: Row(
                                    children: [
                                      Expanded(
                                        child: Text(
                                          item,
                                          style: TextStyle(
                                            fontSize: 14,
                                            color: Colors.grey.shade800,
                                            fontWeight: widget.controller.text == item ? FontWeight.w500 : FontWeight.normal,
                                          ),
                                        ),
                                      ),
                                      if (widget.controller.text == item) Icon(Icons.check, size: 18, color: Theme.of(context).primaryColor),
                                    ],
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
            ),
          ),
        ),
      ),
    );

    Overlay.of(context).insert(_overlayEntry!);
  }

  void _removeOverlay() {
    _overlayVisible = false;
    _overlayEntry?.remove();
    _overlayEntry = null;
    if (mounted) {
      setState(() {
        _hoveredItem = null;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(widget.title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
        const SizedBox(height: 5),
        CompositedTransformTarget(
          link: _layerLink,
          child: TextFormField(
            onTapOutside: (_) {
              _closeTimer?.cancel();
              _closeTimer = Timer(const Duration(milliseconds: 800), _removeOverlay);
            },
            style: TextStyle(
              color: widget.disableDropdown ? Colors.grey.shade500 : Colors.black,
            ),
            controller: widget.controller,
            focusNode: _focusNode,
            validator: widget.validator,
            readOnly: widget.disableDropdown,
            onChanged: widget.onChanged,
            onTap: _showOverlay,
            decoration: InputDecoration(
              hintText: widget.hint,
              hintStyle: TextStyle(color: Colors.grey.shade500),
              suffixIcon: widget.disableDropdown
                  ? null
                  : Icon(Icons.arrow_drop_down, color: Colors.grey.shade600),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: Colors.grey.shade300),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: Colors.grey.shade300),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: AppColor.gray400),
              ),
              contentPadding: const EdgeInsets.all(18),
              filled: true,
              fillColor: Colors.white,
            ),
          ),
        ),
      ],
    );
  }
}
