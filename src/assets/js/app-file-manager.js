/*=========================================================================================
    File Name: app-file-manager.js
    Description: app-file-manager js
==========================================================================================*/

$(function () {
  'use strict';

  var sidebarFileManager = $('.sidebar-file-manager'),
    sidebarToggler = $('.sidebar-toggle'),
    fileManagerOverlay = $('.body-content-overlay'),
    sidebarRight = $('.right-sidebar'),
    viewContainer = $('.view-container'),
    fileManagerItem = $('.file-manager-item'),
    noResult = $('.no-result'),
    fileActions = $('.file-actions'),
    viewToggle = $('.view-toggle'),
    filterInput = $('.files-filter'),
    toggleDropdown = $('.toggle-dropdown'),
    sidebarMenuList = $('.sidebar-list'),
    fileContentBody = $('.file-manager-content-body');

  // Select File
  fileContentBody.find('.custom-control-input').prop('checked', false);
  if (fileManagerItem.length) {
    fileManagerItem.find('.custom-control-input').on('change', function () {
      var $this = $(this);
      if ($this.is(':checked')) {
        $this.closest('.file, .folder').addClass('selected');
      } else {
        $this.closest('.file, .folder').removeClass('selected');
      }
      if (fileManagerItem.find('.custom-control-input:checked').length) {
        fileActions.addClass('show');
      } else {
        fileActions.removeClass('show');
      }
    });
  }

  // Toggle View
  if (viewToggle.length) {
    viewToggle.find('input').on('change', function () {
      var input = $(this);
      viewContainer.each(function () {
        if (!$(this).hasClass('view-container-static')) {
          if (input.is(':checked') && input.data('view') === 'list') {
            $(this).addClass('list-view');
          } else {
            $(this).removeClass('list-view');
          }
        }
      });
    });
  }

  // Filter
  if (filterInput.length) {
    filterInput.on('keyup', function () {
      var value = $(this).val().toLowerCase();

      fileManagerItem.filter(function () {
        var $this = $(this);

        if (value.length) {
          $this.closest('.file, .folder').toggle(-1 < $this.text().toLowerCase().indexOf(value));
          $.each(viewContainer, function () {
            var $this = $(this);
            if ($this.find('.file:visible, .folder:visible').length === 0) {
              $this.find('.no-result').removeClass('d-none').addClass('d-flex');
            } else {
              $this.find('.no-result').addClass('d-none').removeClass('d-flex');
            }
          });
        } else {
          $this.closest('.file, .folder').show();
          noResult.addClass('d-none').removeClass('d-flex');
        }
      });
    });
  }

  // // sidebar file manager list scrollbar
  // if ($(sidebarMenuList).length > 0) {
  //   var sidebarLeftList = new PerfectScrollbar(sidebarMenuList[0], {
  //     suppressScrollX: true
  //   });
  // }

  // if ($(fileContentBody).length > 0) {
  //   var rightContentWrapper = new PerfectScrollbar(fileContentBody[0], {
  //     cancelable: true,
  //     wheelPropagation: false
  //   });
  // }

  // click event for show sidebar
  sidebarToggler.on('click', function () {
    sidebarFileManager.toggleClass('show');
    fileManagerOverlay.toggleClass('show');
  });

  // remove sidebar
  $('.body-content-overlay, .sidebar-close-icon').on('click', function () {
    sidebarFileManager.removeClass('show');
    fileManagerOverlay.removeClass('show');
    sidebarRight.removeClass('show');
  });

  // making active to list item in links on click
  sidebarMenuList.find('.list-group a').on('click', function () {
    if (sidebarMenuList.find('.list-group a').hasClass('active')) {
      sidebarMenuList.find('.list-group a').removeClass('active');
    }
    $(this).addClass('active');
  });

  // Toggle Dropdown
  if (toggleDropdown.length) {
    $('.file-logo-wrapper .dropdown').on('click', function (e) {
      var $this = $(this);
      //e.preventDefault();
      var fileDropdown = $('.file-dropdown', $this);
      if (fileDropdown.length) {
        $('.view-container').find('.file-dropdown').hide();
        //console.log($this.find('.dropdown-menu').show());

        $this.find('.dropdown-menu')
          .show()
          .find('.dropdown-item')
          .on('click', function () {
            $(this).closest('.dropdown-menu').hide();
        });
      }
    });

    // $(document).on('click', function (e) {
    //   if (!$(e.target).hasClass('toggle-dropdown')) {
    //     filesWrapper.find('.file-dropdown').hide();
    //   }
    // });

    if (viewContainer.length) {
      $('.file, .folder').on('mouseleave', function () {
        $(this).find('.file-dropdown').hide();
      });
    }
  }
});
