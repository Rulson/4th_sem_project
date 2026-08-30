import 'package:flutter/material.dart';


class CustomSearchableDropdown extends StatefulWidget {
  final TextEditingController controller;
  final List<String> items;
  final String hint;
  final String title;
  final void Function(String?)? onChanged;
  final String? Function(String?)? validator;
  final bool searchable;
  final bool disableDropdown;

  const CustomSearchableDropdown({
    super.key,
    required this.controller,
    required this.items,
    required this.hint,
    required this.title,
    this.onChanged,
    this.validator,
    this.searchable = true,
    this.disableDropdown = false,
  });

  @override
  State<CustomSearchableDropdown> createState() => _CustomSearchableDropdownState();
}

class _CustomSearchableDropdownState extends State<CustomSearchableDropdown> {
  final LayerLink _layerLink = LayerLink();
  OverlayEntry? _overlayEntry;
  late List<String> _filteredItems;
  final FocusNode _focusNode = FocusNode();
  
  final String _dropdownRegionGroupId = 'custom_dropdown_region';
  final double _fixedDropdownHeight = 250.0; // Fixed height setting

  @override
  void initState() {
    super.initState();
    _filteredItems = widget.items;
    
    _focusNode.addListener(() {
      if (_focusNode.hasFocus && !widget.disableDropdown) {
        _showOverlay();
      } else {
        _removeOverlay();
      }
    });

    if (widget.searchable) {
      widget.controller.addListener(_onSearchChanged);
    }
  }

  @override
  void dispose() {
    _removeOverlay();
    _focusNode.dispose();
    if (widget.searchable) {
      widget.controller.removeListener(_onSearchChanged);
    }
    super.dispose();
  }

  void _onSearchChanged() {
    if (!widget.searchable) return;
    setState(() {
      _filteredItems = widget.items
          .where((item) => item.toLowerCase().contains(widget.controller.text.toLowerCase()))
          .toList();
    });
    _overlayEntry?.markNeedsBuild();
  }

  void _showOverlay() {
    if (_overlayEntry != null) return;

    final RenderBox renderBox = context.findRenderObject() as RenderBox;
    final Size size = renderBox.size;
    final Offset offset = renderBox.localToGlobal(Offset.zero);
    
    final double screenHeight = MediaQuery.of(context).size.height;
    final double keyboardHeight = MediaQuery.of(context).viewInsets.bottom;
    
    // Total space left between the bottom of the text input and the top of the keyboard
    final double availableSpaceBelow = screenHeight - keyboardHeight - offset.dy - size.height;

    // Flip upward only if the fixed height menu won't fit underneath
    bool showAbove = availableSpaceBelow < _fixedDropdownHeight;

    _overlayEntry = OverlayEntry(
      builder: (context) => Positioned(
        width: size.width,
        child: CompositedTransformFollower(
          link: _layerLink,
          showWhenUnlinked: false,
          // If flipping up, offset Y shifts exactly by the fixed menu height + minor gap
          offset: Offset(0.0, showAbove ? -(_fixedDropdownHeight + 5) : size.height-14),
          child: TapRegion(
            groupId: _dropdownRegionGroupId,
            child: Material(
              elevation: 8,
              borderRadius: BorderRadius.circular(12),
              child: Container(
                height: _fixedDropdownHeight, // Strictly locked height
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: Colors.grey.shade200),
                ),
                child: _filteredItems.isEmpty
                    ? const Center(
                        child: Padding(
                          padding: EdgeInsets.all(16.0),
                          child: Text('No items found', style: TextStyle(color: Colors.grey, fontSize: 14)),
                        ),
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(vertical: 6),
                        shrinkWrap: true,
                        itemCount: _filteredItems.length,
                        itemBuilder: (context, index) {
                          final item = _filteredItems[index];
                          return InkWell(
                            onTap: () {
                              widget.controller.text = item;
                              widget.onChanged?.call(item);
                              _focusNode.unfocus();
                            },
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
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
                                  if (widget.controller.text == item)
                                    Icon(Icons.check, size: 18, color: Theme.of(context).primaryColor),
                                ],
                              ),
                            ),
                          );
                        },
                      ),
              ),
            ),
          ),
        ),
      ),
    );

    Overlay.of(context).insert(_overlayEntry!);
  }

  void _removeOverlay() {
    if (_overlayEntry != null) {
      _overlayEntry!.remove();
      _overlayEntry = null;
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
          child: TapRegion(
            groupId: _dropdownRegionGroupId,
            onTapOutside: (_) => _focusNode.unfocus(),
            child: TextFormField(
              style: TextStyle(
                color: widget.disableDropdown ? Colors.grey.shade500 : Colors.black,
              ),
              controller: widget.controller,
              focusNode: _focusNode,
              validator: widget.validator,
              readOnly: !widget.searchable || widget.disableDropdown,
              onTap: widget.disableDropdown ? null : () {
                if (!_focusNode.hasFocus) {
                  _focusNode.requestFocus();
                } else {
                  _showOverlay();
                }
              },
              decoration: InputDecoration(
                hintText: widget.hint,
                hintStyle: TextStyle(color: Colors.grey.shade500),
                suffixIcon: (!widget.disableDropdown) ? Icon(Icons.arrow_drop_down, color: Colors.grey.shade600) : null,
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.grey.shade300)),
                enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.grey.shade300)),
                focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: const BorderSide(color: Colors.grey)),
                contentPadding: const EdgeInsets.all(18),
                filled: true,
                fillColor: Colors.white,
              ),
            ),
          ),
        ),
      ],
    );
  }
}