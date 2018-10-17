<?php

include("../config.php");
//function to add one row on the table grid for input
if ($_GET['func'] == 'add') {
    $currentDate = date('d/m/Y');
    $html = '<tr class="odd add-new-row">
                <td class="dt-center">-</td>
                <td><input type="text" class="form-control col-lg-11" placeholder="Name" maxlength="200" name="sname" id="sname"></td>
                <td>
                    <div class="input-group date" id="dp3" data-date="' . $currentDate . '" data-date-format="dd/mm/yyyy">
                        <input class="form-control" type="text" name="sdob" readonly="" value="' . $currentDate . '">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </td>
                <td class="dt-center"><input type="text" class="form-control" placeholder="Nationality" name="snationality" id="snationality"></td>
                <td><input type="text" class="form-control" placeholder="Mobile No." maxlength="20" name="smobile" id="smobile"></td>
                <td style="white-space: nowrap; width: 1%;" class="tabledit-view-mode">
                     <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                        <div class="btn-group btn-group-sm" style="float: none;">
                            <button type="submit" class="btn btn-sm btn-default" style="float: none;"><span class="glyphicon glyphicon-ok"></span></button>
                            <button type="button" class="btn btn-sm btn-default cancelAddStudent" style="float: none;"><span class="glyphicon glyphicon-remove"></span></button>
                        </div>                                       
                    </div>
                </td>
            </tr>';
    exit($html);
}

if ($_GET['func'] == 'tabledit') {
    $input = filter_input_array(INPUT_POST);
    if ($input["action"] === 'edit') {
        $cdate = strtotime(str_replace('/', '-', $input["cdate"]));
        $date = date("Y-m-d", $cdate);
        $sql = "UPDATE student SET sname = '" . $input["sname"] . "', snationality = '" . $input["snationality"] . "', sdob = '" . $date . "', smobile = '" . $input["smobile"] . "' WHERE sid = '" . $input["sid"] . "'";
    }
    if ($input["action"] === 'delete') {
        $sql = "DELETE FROM student WHERE sid = '" . $input["sid"] . "'";
    }
    if ($conn->query($sql) === TRUE) {
        $input["result"] = 'success';
    } else {
        $input["result"] = $conn->error;
    }

    $conn->close();

    exit(json_encode($input));
}
?>