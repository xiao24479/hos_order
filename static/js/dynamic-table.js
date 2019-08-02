var Script = function () {



        // begin first table

        $('#sample_1').dataTable({

            "sDom": "<'row-fluid'<'span1'l><'span3'f>r>t<'row-fluid'<'span4'i><'span4'p>>",

            "sPaginationType": "bootstrap",

            "oLanguage": {

                "sLengthMenu": "显示 _MENU_",

                "oPaginate": {

                    "sPrevious": "上页",

                    "sNext": "下页"

                }

            },

            "aoColumnDefs": [{

                'bSortable': false,

                'aTargets': [0]

            }]

        });



        jQuery('#sample_1 .group-checkable').change(function () {

            var set = jQuery(this).attr("data-set");

            var checked = jQuery(this).is(":checked");

            jQuery(set).each(function () {

                if (checked) {

                    $(this).attr("checked", true);

                } else {

                    $(this).attr("checked", false);

                }

            });

            jQuery.uniform.update(set);

        });



        jQuery('#sample_1_wrapper .dataTables_filter input').addClass("input-medium"); // modify table search input

        jQuery('#sample_1_wrapper .dataTables_length select').addClass("input-mini"); // modify table per page dropdown



        // begin second table

        $('#sample_2').dataTable({

            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",

            "sPaginationType": "bootstrap",

            "oLanguage": {

                "sLengthMenu": "_MENU_ 每页",

                "oPaginate": {

                    "sPrevious": "Prev",

                    "sNext": "Next"

                }

            },

            "aoColumnDefs": [{

                'bSortable': false,

                'aTargets': [0]

            }]

        });



        jQuery('#sample_2 .group-checkable').change(function () {

            var set = jQuery(this).attr("data-set");

            var checked = jQuery(this).is(":checked");

            jQuery(set).each(function () {

                if (checked) {

                    $(this).attr("checked", true);

                } else {

                    $(this).attr("checked", false);

                }

            });

            jQuery.uniform.update(set);

        });



        jQuery('#sample_2_wrapper .dataTables_filter input').addClass("input-small"); // modify table search input

        jQuery('#sample_2_wrapper .dataTables_length select').addClass("input-mini"); // modify table per page dropdown



        // begin: third table

        $('#sample_3').dataTable({

            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",

            "sPaginationType": "bootstrap",

            "oLanguage": {

                "sLengthMenu": "_MENU_ per page",

                "oPaginate": {

                    "sPrevious": "Prev",

                    "sNext": "Next"

                }

            },

            "aoColumnDefs": [{

                'bSortable': false,

                'aTargets': [0]

            }]

        });



        jQuery('#sample_3 .group-checkable').change(function () {

            var set = jQuery(this).attr("data-set");

            var checked = jQuery(this).is(":checked");

            jQuery(set).each(function () {

                if (checked) {

                    $(this).attr("checked", true);

                } else {

                    $(this).attr("checked", false);

                }

            });

            jQuery.uniform.update(set);

        });



        jQuery('#sample_3_wrapper .dataTables_filter input').addClass("input-small"); // modify table search input

        jQuery('#sample_3_wrapper .dataTables_length select').addClass("input-mini"); // modify table per page dropdown


        // begin first table
        $('#sample_4').dataTable({
            "sDom": "<'row-fluid'<'span1'l><'span3'f>r>t<'row-fluid'<'span4'i><'span4'p>>",
            "bPaginate" : false,
            "bInfo" : false,
            "bSort":false,
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "显示 _MENU_",
                "oPaginate": {
                    "sPrevious": "上页",
                    "sNext": "下页"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }]
        });

        jQuery('#sample_4 .group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
            });
            jQuery.uniform.update(set);
        });

        jQuery('#sample_4_wrapper .dataTables_filter input').addClass("input-medium"); // modify table search input
        jQuery('#sample_4_wrapper .dataTables_length select').addClass("input-mini"); // modify table per page dropdown




}();