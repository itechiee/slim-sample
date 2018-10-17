<?php include("config.php"); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link id="favicon" rel="shortcut icon" href="images/favicon.png" type="image/png" />
        <title>Student Management System</title>
        <!-- BOOTSTRAP STYLES-->
        <link href="css/bootstrap.css" rel="stylesheet" />
        <link href="css/font-awesome.css" rel="stylesheet" />
        <link href="css/custom.css" rel="stylesheet" />
        <link href="css/custom-trs.css" rel="stylesheet" />        
        <link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="css/datepicker.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-1.12.4.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery-ui-1.10.1.custom.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.blockUI.js"></script>
        <script type="text/javascript" language="javascript" src="js/moment.min.js"></script>
        <script type="text/javascript" language="javascript" src="js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="js/bootbox.min.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.tabledit.js"></script>
        <script type="text/javascript">
            $(document).ajaxStop($.unblockUI);
            $(function() {
                $("#header").load("view/header.php");
                $("#sidebar").load("view/sidebar.php");

                $(".input-group.date").datepicker({
                    autoclose: true, todayHighlight: true
                });

                function restoreRow(oTable, nRow) {
                    var aData = oTable.fnGetData(nRow);
                    var jqTds = $('>td', nRow);

                    for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                        oTable.fnUpdate(aData[i], nRow, i, false);
                    }

                    oTable.fnDraw();
                }

                $('#tableS').Tabledit({
                    url: 'php/controller.php?func=tabledit',
                    columns: {
                        identifier: [0, 'sid'],
                        editable: [
                            [2, 'sname'], [3, 'sdob'], [4, 'snationality'], [5, 'smobile']
                        ]
                    },
                    hideIdentifier: true,
                    groupClass: 'btn-group btn-group-sm hideButton',
                    onDraw: function() {
                        $('table tr td:nth-child(4) input').each(function() {
                            $(this).datepicker({
                                format: 'dd/mm/yyyy',
                                todayHighlight: true,
                                autoclose: true
                            });
                        });
                    },
                    restoreButton: false,
                    onSuccess: function(data, textStatus, jqXHR)
                    {
                        var alert = '';
                        if (data.result == 'success') {
                            if (data.action == 'delete') {
                                alert = 'Student deleted!';
                                t.row($('#' + data.sid)).remove().draw();
                            } else {
                                t.row($('#' + data.sid)).invalidate().draw(false);
                            }
                            setTimeout(function() {
                                bootbox.alert(alert);
                            }, 1500);
                            console.log(textStatus);
                        } else {
                            bootbox.alert("Action failed! " + data.result);
                            // Remove class "deleted" to row.
                            $("#tableS tr.tabledit-deleted-row").removeClass('tabledit-deleted-row');
                            $("#tableS tr.text-muted").find('.tabledit-toolbar button').attr('disabled', false);
                            $("#tableS tr.text-muted").removeClass('text-muted');
                        }
                    }
                });

                var t = $('#tableS').DataTable({
                    "columnDefs": [
                        {targets: [0, 1, 6], searchable: false, orderable: false}
                    ],
                    "sDom": "<'row-fluid'Bf>ti<'row-fluid'<'span12'>p>",
                    "pageLength": 20,
                    "order": [[2, 'desc']],
                    buttons: [
                        {
                            text: 'Add New Student',
                            className: 'addStudent',
                            action: function(e, dt, node, config) {
                                e.preventDefault();
                                $.blockUI({message: '<span style="font-size: 14px; margin:5px; font-weight: bold;color: #006699;"><img src=images/loading.gif /> Loading. Please wait...</span>'});
                                $.ajax({
                                    url: "php/controller.php?func=add",
                                    success: function(result) {
                                        $('#tableS tr:first').after(result);
                                        $(".input-group.date").datepicker({autoclose: true, todayHighlight: true});
                                        $(".hideButton").hide();
                                    }
                                });
                            }
                        }]
                });

                t.on('order.dt search.dt', function() {
                    t.column(1, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            });
        </script>
    </head>
    <body>  
        <div id="wrapper">
            <div id="header"></div> 
            <div id="sidebar"></div> 
            <div id="page-wrapper" >
                <div id="page-inner">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-head">
                                <h3 style="font-size: 1.25em">Maintenance</h3>
                            </div> 
                        </div>
                    </div>              
                    <div class="row">
                        <div class="col-lg-12 ">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <form method="POST" action="" class="form-horizontal" id="form-student">
                                    <?php
                                    $sql = "SELECT * FROM student";
                                    $result = $conn->query($sql);
                                    $counter = 1;
                                    ?>
                                    <table id="tableS" class="display" cellspacing="0" width="100%">
                                        <thead>
                                            <tr role="row">
                                                <th>id</th>
                                                <th width="5%">#</th>
                                                <th>Name</th>
                                                <th width="20%">DOB</th>
                                                <th width="18%">Nationality</th>
                                                <th width="15%">Mobile No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) { ?>
                                                <tr class="<?php echo ($counter % 2 == 0 ? "even" : "odd") ?>">
                                                    <td><?php echo $row['sid']; ?></td>
                                                    <td><?php echo $counter++; ?></td>
                                                    <td><?php echo $row['sname']; ?></td>
                                                    <?php
                                                    $newdate = date('d/m/Y', strtotime($row['sdob'])); // Format in which I want to display
                                                    $dateOrder = $row['sdob']; // Sort Order
                                                    ?>
                                                    <td style="white-space:nowrap" data-order="<?php echo $dateOrder; ?>" ><?php echo $newdate; ?></td>
                                                    <td><?php echo $row['snationality']; ?></td>
                                                    <td><?php echo $row['smobile']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="row">
                <div class="col-lg-12" >
                    &copy;2018 Designed by: Bakhtiar
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
        <!-- CUSTOM SCRIPTS -->
        <script src="js/custom.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {

                jQuery.validator.addMethod("noSpace", function(value, element) {
                    return value.indexOf(" ") < 0 && value != "";
                }, "No space!");

                var form = $("#form-student");
                form.validate({
                    ignore: [],
                    rules: {
                        sname: {
                            required: true
                        },
                        sdob: {
                            required: true
                        },
                        snationality: {
                            required: true
                        },
                        smobile: {
                            required: true
                        }
                    },
                    messages: {
                        sname: {
                            required: "Mandatory!"
                        },
                        sdob: {
                            required: "Mandatory!"
                        },
                        snationality: {
                            required: "Mandatory!"
                        },
                        smobile: {
                            required: "Mandatory!"
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter($(element));
                    },
                    submitHandler: function(form) {
                        var $row = $(this).closest('tr');
                        $.blockUI({message: '<span style="font-size: 14px; margin:5px; font-weight: bold;color: #006699;"><img src="images/loading.gif" /> Loading. Please wait...</span>'});
                        var $url = "api/add.php";
                        $.ajax({
                            type: "POST",
                            url: $url,
                            data: $("#form-student").serialize(),
                            success: function(data) {
                                if (data.response == 'success') {
                                    bootbox.alert("Student added!");
                                    setTimeout(function() {
                                        window.location = 'index.php';
                                    }, 1500);
                                } else {
                                    bootbox.alert("Student add failed! " + data);
                                    $row.remove();
                                    $(".hideButton").show();
                                }
                            }
                        });
                    }
                });

                $(document).on("click", ".cancelAddStudent", function(e) {
                    e.preventDefault();
                    var $row = $(this).closest('tr');
                    $.ajax({
                        success: function(result) {
                            $row.remove();
                            $(".hideButton").show();
                        }});
                });
            });
        </script>
    </body>
</html>
