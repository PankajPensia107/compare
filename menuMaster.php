<?php include "../include/configurations.php";
define("CUR_PAGE", "ManageMenuMaster");
include $baseROOT . "/SuperAdmin/include/loginAdminVerification.php";
include $baseROOT . "/SuperAdmin/include/header.php";
include $baseROOT . "/SuperAdmin/logic/logicMenuMaster.php";
?>
<script type="text/javascript">
    function getSession(val) {

        var xmlhttp;
        var schoolCode = val;
        //alert(className);
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var res = xmlhttp.responseText;
                document.getElementById("response").innerHTML = res;
            }
        }
        xmlhttp.open("GET", "ajax_search.php?schoolCode=" + schoolCode, true);
        xmlhttp.send();
    }
</script>
<script>
    checked = true;
    feechecked = true;
    webchecked = true;
    setchecked = true;
    examchecked = true;
    accountschecked = true;

    function checkUncheckAll(field) {
        //alert(document.getElementById(field).elements.length);
        if (checked == false) {
            checked = true;
        } else {
            checked = false;
        }
        for (var i = 0; i < document.getElementById(field).elements.length; i++) {
            document.getElementById(field).elements[i].checked = checked;
        }
    }

    function checkUncheckFee(className) {
        if (feechecked == false) {
            feechecked = true;
        } else {
            feechecked = false;
        }
        $("." + className).prop("checked", feechecked);
    }

    function checkUncheckWeb(className) {

        $("." + className).prop("checked", $("#chkwebcnt").prop("checked"));
    }

    function selectAllWeb(className) {

        $("." + className).prop("checked", $("#selectWeb").prop("checked"));
    }

    function checkUncheckSet(className) {

        $("." + className).prop("checked", $("#chksting").prop("checked"));
    }

    function checkUncheckExam(className) {
        $("." + className).prop("checked", $("#chkexm").prop("checked"));
    }

    function selectAllExam(className) {
        $("." + className).prop("checked", $("#selectExam").prop("checked"));
    }

    function checkUncheck(className, id) {


        $("." + className).prop("checked", $("#" + id).prop("checked"));
    }



    function checkUncheckAccounts(className) {


        $("." + className).prop("checked", $("#chkacnt").prop("checked"));
    }

    // validation for download
    function validate() {
        var chks = document.getElementsByName('check_list[]');
        var hasChecked = false;

        for (var i = 0; i < chks.length; i++) {
            if (chks[i].checked) {
                hasChecked = true;
                break;
            }
        }
        if (hasChecked == false) {
            alert("Please select at least one row.");
            return false;
        }
        return true;
    }
</script>
<!-- end #headerContent -->
<!-- Dashboard wrapper starts -->
<div class="dashboard-wrapper">
    <!-- Main container starts -->
    <div class="main-container">
        <!-- Row starts -->
        <?php
        switch ($section) {
            case "delete":
                if(isset($_GET["ID"])){
                    if($MenuSettingObj->deleteMenuSetting($_GET["ID"])){
                        echo '<script>alert("Menu Setting deleted successfully.");window.location.href = "?mes=delSetting";</script>';
                    }
                };
                break;
            case "add"    :
            case "edit" :
            $menuData = [];
            $ID = $_GET['ID'];
            if ($section == 'edit') {
                $CurrentMenuSetting = $MenuSettingObj->getMenuSettingByID($ID);
                $action = "?section=$section&ID=$ID";
                $menuData = explode("||", $CurrentMenuSetting['menuSetting']);
//                print_r($menuData); die();
            } else
                $action = "?section=$section";

            ?>
                <div class="row gutter">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h4><?= ucfirst($section); ?> Menu Setting</h4>
                                <span style="float:right; margin-top:-20px;"> <a href="?section=view"
                                                                                 class="colorRed links"
                                                                                 title="View Setting"><img
                                                src="<?= $baseURL; ?>/image/back.png" width="16" height="16"
                                                alt="back"/></a></span>
                            </div>
                            <div class="panel-body">
                                <?php include $baseROOT . "/include/validationMassage.php"; ?>
                                <form name="frm" id="frm" action="<?= $action ?>" method="post"
                                      onSubmit="return validateFields(this, rules);" enctype="multipart/form-data">
                                    <fieldset>
                                        <div class="form-group row gutter">
                                            <label class="col-lg-1 control-label">*School Code</label>
                                            <div class="col-lg-2">
                                                <select name="schoolCode" id="schoolCode" class="form-control"
                                                        onchange="getSession(this.value)">
                                                    <option value="">Select School Code</option>
                                                    <?php $sql = mysqli_query(mysqli, "SELECT DISTINCT schoolCode FROM " . tblManageAdmin . " WHERE active = '1' AND schoolCode != 'SUPER'");
                                                    while ($schoolData = mysqli_fetch_array($sql)) {
                                                        ?>
                                                        <option value="<?= $schoolData['schoolCode'] ?>" <?= (($CurrentMenuSetting['schoolCode'] == $schoolData['schoolCode']) || ($_POST['schoolCode'] == $schoolData['schoolCode'])) ? 'selected' : ''; ?>><?= $schoolData['schoolCode'] ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                            <div class="col-lg-1">&nbsp;</div>
                                            <label class="col-lg-2 control-label">*Session Start Date</label>
                                            <div class="col-lg-2">
                                                <span id="response">
                                               <select name="sessionStartDate" id="sessionStartDate"
                                                       class="form-control">
                                                <option value="">Select Session Start Date</option>
                                                <?php if ($section == "edit") {
                                                    $qury = "select DISTINCT newSessionStartDate from " . tblManageSession . " WHERE schoolCode='" . $CurrentMenuSetting['schoolCode'] . "'";
                                                    $sql = mysqli_query(mysqli, $qury);
                                                    while ($data = mysqli_fetch_array($sql)) {
                                                        ?>
                                                        <option value='<?= $data['newSessionStartDate'] ?>' <?= (($CurrentMenuSetting['sessionStartDate'] == $data['newSessionStartDate']) || ($_POST['sessionStartDate'] == $data['newSessionStartDate'])) ? 'selected' : ''; ?> ><?= $data['newSessionStartDate'] ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </select>
                                                </span>
                                            </div>
                                        </div>
                                        <h3>Setting For Menu</h3>
                                        <div class="form-group row gutter">
                                            <label class="col-lg-3 control-label forlabel">All&nbsp;&nbsp;<input
                                                        type="checkbox" checked="checked" name="CheckAll" id="CheckAll"
                                                        onClick="checkUncheckAll('frm');"></label>


                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h4>Student</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="checkbox" name="menuName[Student]"
                                                               value="1" <?= in_array("Student=1", $menuData) ? 'checked' : '' ?> />
                                                        <label for="Add" class="permisnCheck">ADD</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h4>Staff</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="checkbox" name="menuName[Staff]"
                                                               value="1" <?= in_array("Staff=1", $menuData) ? 'checked' : '' ?>/>
                                                        <label for="Add" class="permisnCheck">ADD</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h4>Fees</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="checkbox" name="menuName[Fees]"
                                                               onClick="checkUncheckFee('Fees');"
                                                               value="1" <?= in_array("Fees=1", $menuData) ? 'checked' : '' ?>/>
                                                        <label for="Add" class="permisnCheck">ADD</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-1">Accounts</h4>
                                                            <input type="checkbox" id="chkacnt"
                                                                   name="menuName[Accounts]"
                                                                   value="1" <?= in_array("Accounts=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheckAccounts('Accounts');"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <b>Trial Balance</b><br>
                                                            <input type="checkbox" name="menuName[TrialBalance]"
                                                                   value="1"
                                                                   class="Accounts" <?= in_array("TrialBalance=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Profit Loss</b><br>
                                                            <input type="checkbox" name="menuName[ProfitLoss]" value="1"
                                                                   class="Accounts" <?= in_array("ProfitLoss=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>BalanceSheet</b><br>
                                                            <input type="checkbox" name="menuName[BalanceSheet]"
                                                                   value="1"
                                                                   class="Accounts" <?= in_array("BalanceSheet=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Outstanding Analysis</b><br>
                                                            <input type="checkbox" name="menuName[OutstandingAnalysis]"
                                                                   value="1"
                                                                   class="Accounts" <?= in_array("OutstandingAnalysis=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h4>Attendance</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="checkbox" name="menuName[Attendance]"
                                                               value="1" <?= in_array("Attendance=1", $menuData) ? 'checked' : '' ?> />
                                                        <label for="Add" class="permisnCheck">ADD</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h4>Transport</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="checkbox" name="menuName[Transport]"
                                                               value="1" <?= in_array("Transport=1", $menuData) ? 'checked' : '' ?> />
                                                        <label for="Add" class="permisnCheck">ADD</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h4>Library</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="checkbox" name="menuName[Library]"
                                                               value="1" <?= in_array("Library=1", $menuData) ? 'checked' : '' ?> />
                                                        <label for="Add" class="permisnCheck">ADD</label>
                                                        <input type="checkbox" name="menuName[LibraryForAdmin]"
                                                               value="1" <?= in_array("LibraryForAdmin=1", $menuData) ? 'checked' : '' ?> />
                                                        <label for="Add" class="permisnCheck">ADD FOR ADMIN</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h4>Dashboard</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="checkbox" name="menuName[Dashboard]"
                                                               value="1" <?= in_array("Dashboard=1", $menuData) ? 'checked' : '' ?> />
                                                        <label for="Add" class="permisnCheck">ADD</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Top Menu</h4>
                                                            <input type="checkbox" id="topMenuAll"
                                                                   name="menuName[TopMenuAll]"
                                                                   value="1" <?= in_array("TopMenuAll=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('topMenuAll',this.id);"/>
                                                            <label for="topMenuAll"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="smsPanel"
                                                                   name="menuName[smsPanel]"
                                                                   value="1" <?= in_array("smsPanel=1", $menuData) ? 'checked' : '' ?>  class="topMenuAll"/>
                                                            <label for="smsPanel" class="permisnCheck">SMS Panel</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MsgHmwrkPanel"
                                                                   name="menuName[MsgHmwrkPanel]"
                                                                   value="1" <?= in_array("MsgHmwrkPanel=1", $menuData) ? 'checked' : '' ?>  class="topMenuAll"/>
                                                            <label for="MsgHmwrkPanel" class="permisnCheck">Message/Homework/Notice Panel</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="backupPanel"
                                                                   name="menuName[backupPanel]"
                                                                   value="1" <?= in_array("backupPanel=1", $menuData) ? 'checked' : '' ?>  class="topMenuAll"/>
                                                            <label for="backupPanel" class="permisnCheck">Backup</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StffLeavePnl"
                                                                   name="menuName[StffLeavePnl]"
                                                                   value="1" <?= in_array("StffLeavePnl=1", $menuData) ? 'checked' : '' ?>  class="topMenuAll"/>
                                                            <label for="StffLeavePnl" class="permisnCheck">Staff Leave</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StdLeavePnl"
                                                                   name="menuName[StdLeavePnl]"
                                                                   value="1" <?= in_array("StdLeavePnl=1", $menuData) ? 'checked' : '' ?>  class="topMenuAll"/>
                                                            <label for="StdLeavePnl" class="permisnCheck">Student Leave</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StdFeedbkPnl"
                                                                   name="menuName[StdFeedbkPnl]"
                                                                   value="1" <?= in_array("StdFeedbkPnl=1", $menuData) ? 'checked' : '' ?>  class="topMenuAll"/>
                                                            <label for="StdFeedbkPnl" class="permisnCheck">Student Feedback</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StdEnquiryPnl"
                                                                   name="menuName[StdEnquiryPnl]"
                                                                   value="1" <?= in_array("StdEnquiryPnl=1", $menuData) ? 'checked' : '' ?>  class="topMenuAll"/>
                                                            <label for="StdEnquiryPnl" class="permisnCheck">Student Enquiry</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Student</h4>
                                                            <input type="checkbox" id="studenmasterall"
                                                                   name="menuName[StudentMaster]"
                                                                   value="1" <?= in_array("StudentMaster=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('studentmaster',this.id);"/>
                                                            <label for="studenmasterall"
                                                                   class="permisnCheck">ADD</label>

                                                            <input type="checkbox" id="selectStudentAll"
                                                                   onClick="checkUncheck('studentmaster',this.id);"/>
                                                            <label for="studenmasterall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageClass"
                                                                   name="menuName[ManageClass]"
                                                                   value="1" <?= in_array("ManageClass=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageClass" class="permisnCheck">Manage
                                                                Class</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageSection"
                                                                   name="menuName[ManageSection]"
                                                                   value="1" <?= in_array("ManageSection=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageSection" class="permisnCheck">Manage
                                                                Section</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageCategory"
                                                                   name="menuName[ManageCategory]"
                                                                   value="1" <?= in_array("ManageCategory=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageCategory" class="permisnCheck">Manage
                                                                Category</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageGender"
                                                                   name="menuName[ManageGender]"
                                                                   value="1" <?= in_array("ManageGender=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageGender" class="permisnCheck">Manage Gender</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageSubCaste"
                                                                   name="menuName[ManageSubCaste]"
                                                                   value="1" <?= in_array("ManageSubCaste=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageSubCaste" class="permisnCheck">Manage Sub Caste</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageCity"
                                                                   name="menuName[ManageCity]"
                                                                   value="1" <?= in_array("ManageCity=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageCity" class="permisnCheck">Manage City</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageHouse"
                                                                   name="menuName[ManageHouse]"
                                                                   value="1" <?= in_array("ManageHouse=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageHouse" class="permisnCheck">Manage
                                                                House</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageSubject"
                                                                   name="menuName[ManageSubject]"
                                                                   value="1" <?= in_array("ManageSubject=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageSubject" class="permisnCheck">Manage
                                                                Subject</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageGroup"
                                                                   name="menuName[ManageGroup]"
                                                                   value="1" <?= in_array("ManageGroup=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageGroup" class="permisnCheck">Manage
                                                                Group</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageFine"
                                                                   name="menuName[ManageFine]"
                                                                   value="1" <?= in_array("ManageFine=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageFine" class="permisnCheck">Manage
                                                                Fine</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageFeeReference"
                                                                   name="menuName[ManageFeeReference]"
                                                                   value="1" <?= in_array("ManageFeeReference=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageFeeReference" class="permisnCheck">Manage
                                                                Fee Reference</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageScholarType"
                                                                   name="menuName[ManageScholarType]"
                                                                   value="1" <?= in_array("ManageScholarType=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageScholarType" class="permisnCheck">Manage
                                                                Scholar Type</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageScholarTypeSection"
                                                                   name="menuName[ManageScholarTypeSection]"
                                                                   value="1" <?= in_array("ManageScholarTypeSection=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageScholarTypeSection" class="permisnCheck">Manage
                                                                Scholar Type Section</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManagePromoteClassName"
                                                                   name="menuName[ManagePromoteClassName]"
                                                                   value="1" <?= in_array("ManagePromoteClassName=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManagePromoteClassName" class="permisnCheck">Manage
                                                                Promote Class Name</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageDiscontinueReason"
                                                                   name="menuName[ManageDiscontinueReason]"
                                                                   value="1" <?= in_array("ManageDiscontinueReason=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentmaster"/>
                                                            <label for="ManageDiscontinueReason" class="permisnCheck">Manage
                                                                Discontinue Reason</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Student Entry</h4>
                                                            <input type="checkbox" id="studententryall"
                                                                   name="menuName[StudentEntry]"
                                                                   value="1" <?= in_array("StudentEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('studententry',this.id);"/>
                                                            <label for="studententryall"
                                                                   class="permisnCheck">ADD</label>

                                                            <input type="checkbox" id="selectEntry"
                                                                    onClick="checkUncheck('studententry',this.id);"/>
                                                            <label for="studententryall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="EnquiryForm"
                                                                   name="menuName[EnquiryForm]"
                                                                   value="1" <?= in_array("EnquiryForm=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="EnquiryForm" class="permisnCheck">Enquiry
                                                                Form</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AdmissionForm"
                                                                   name="menuName[AdmissionForm]"
                                                                   value="1" <?= in_array("AdmissionForm=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="AdmissionForm" class="permisnCheck">Admission
                                                                Form</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="UpdateStdData"
                                                                   name="menuName[UpdateStdData]"
                                                                   value="1" <?= in_array("UpdateStdData=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="UpdateStdData" class="permisnCheck">Update
                                                                Student Data</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="UpdateStdOldBalance"
                                                                   name="menuName[UpdateStdOldBalance]"
                                                                   value="1" <?= in_array("UpdateStdOldBalance=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="UpdateStdOldBalance" class="permisnCheck">Update Old Balance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DifferOldTransportBalance"
                                                                   name="menuName[DifferOldTransportBalance]"
                                                                   value="1" <?= in_array("DifferOldTransportBalance=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="DifferOldTransportBalance" class="permisnCheck">Differ Old & Transport Balance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="clsWseSubAssgn"
                                                                   name="menuName[clsWseSubAssgn]"
                                                                   value="1" <?= in_array("clsWseSubAssgn=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="clsWseSubAssgn" class="permisnCheck">Class Wise
                                                                Subject Assign</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="clsWseOpSubAssgn"
                                                                   name="menuName[clsWseOpSubAssgn]"
                                                                   value="1" <?= in_array("clsWseOpSubAssgn=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="clsWseOpSubAssgn" class="permisnCheck">Class
                                                                Wise Optional Subject Assign</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="tccertificate"
                                                                   name="menuName[tccertificate]"
                                                                   value="1" <?= in_array("tccertificate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="tccertificate" class="permisnCheck">TC
                                                                Certificate</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SLCertificate"
                                                                   name="menuName[SLCertificate]"
                                                                   value="1" <?= in_array("SLCertificate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="SLCertificate" class="permisnCheck">School
                                                                Leaving Certificate</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ConsentFormEntry"
                                                                   name="menuName[ConsentFormEntry]"
                                                                   value="1" <?= in_array("ConsentFormEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="ConsentFormEntry" class="permisnCheck">Consent
                                                                Form</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BirthCertificate"
                                                                   name="menuName[BirthCertificate]"
                                                                   value="1" <?= in_array("BirthCertificate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="BirthCertificate" class="permisnCheck">Birth
                                                                Certificate</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CharacterCertificate"
                                                                   name="menuName[CharacterCertificate]"
                                                                   value="1" <?= in_array("CharacterCertificate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="CharacterCertificate" class="permisnCheck">Character
                                                                Certificate</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="WCertificate"
                                                                   name="menuName[WCertificate]"
                                                                   value="1" <?= in_array("WCertificate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="WCertificate" class="permisnCheck">Withdrawal
                                                                Certificate</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AppearingCertificate"
                                                                   name="menuName[AppearingCertificate]"
                                                                   value="1" <?= in_array("AppearingCertificate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="AppearingCertificate" class="permisnCheck">Appearing
                                                                Certificate</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="VerAuthCard"
                                                                   name="menuName[VerAuthCard]"
                                                                   value="1" <?= in_array("VerAuthCard=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studententry"/>
                                                            <label for="VerAuthCard" class="permisnCheck">Verify
                                                                Authority Card/Gatepass</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">

                                                            <h4 class="col-md-2">Student Report</h4>
                                                            <input type="checkbox" id="studentreportyall"
                                                                   name="menuName[StudentReport]"
                                                                   value="1" <?= in_array("StudentReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('studentreport',this.id);"/>
                                                            <label for="studentreportyall"
                                                                   class="permisnCheck">ADD</label>

                                                            <input type="checkbox" id="selectStudent"
                                                                   onClick="checkUncheck('studentreport',this.id);"/>
                                                            <label for="studentreportyall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="EnquiryList"
                                                                   name="menuName[EnquiryList]"
                                                                   value="1" <?= in_array("EnquiryList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="EnquiryList" class="permisnCheck">Enquiry
                                                                List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AdmissionList"
                                                                   name="menuName[AdmissionList]"
                                                                   value="1" <?= in_array("AdmissionList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="AdmissionList" class="permisnCheck">Admission
                                                                List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="UniExamRslt"
                                                                   name="menuName[UniExamRslt]"
                                                                   value="1" <?= in_array("UniExamRslt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="UniExamRslt" class="permisnCheck">University Exam Result</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="UniStuStfInfo"
                                                                   name="menuName[UniStuStfInfo]"
                                                                   value="1" <?= in_array("UniStuStfInfo=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="UniStuStfInfo" class="permisnCheck">University Student/Staff INfo</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BirthDayList"
                                                                   name="menuName[BirthDayList]"
                                                                   value="1" <?= in_array("BirthDayList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="BirthDayList" class="permisnCheck">Birth Day
                                                                List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AllinOneStudentList"
                                                                   name="menuName[AllinOneStudentList]"
                                                                   value="1" <?= in_array("AllinOneStudentList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="AllinOneStudentList" class="permisnCheck">All in
                                                                One Student List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AdmTypeWiseStdReport"
                                                                   name="menuName[AdmTypeWiseStdReport]"
                                                                   value="1" <?= in_array("AdmTypeWiseStdReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="AdmTypeWiseStdReport" class="permisnCheck">Adm
                                                                Type Wise Student Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AgeWiseStdReport"
                                                                   name="menuName[AgeWiseStdReport]"
                                                                   value="1" <?= in_array("AgeWiseStdReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="AgeWiseStdReport" class="permisnCheck">Age Wise
                                                                Student Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AgeWise2"
                                                                   name="menuName[AgeWise2]"
                                                                   value="1" <?= in_array("AgeWise2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="AgeWise2" class="permisnCheck">Age Wise
                                                                2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsWiseStrengthRpt"
                                                                   name="menuName[ClsWiseStrengthRpt]"
                                                                   value="1" <?= in_array("ClsWiseStrengthRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="ClsWiseStrengthRpt" class="permisnCheck">Class
                                                                Wise Strength Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="HouseWiseStrengthRpt"
                                                                   name="menuName[HouseWiseStrengthRpt]"
                                                                   value="1" <?= in_array("HouseWiseStrengthRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="HouseWiseStrengthRpt" class="permisnCheck">House
                                                                Wise Strength Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsWiseCasteRpt"
                                                                   name="menuName[ClsWiseCasteRpt]"
                                                                   value="1" <?= in_array("ClsWiseCasteRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="ClsWiseCasteRpt" class="permisnCheck">Class Wise
                                                                Caste Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsWiseReligionRpt"
                                                                   name="menuName[ClsWiseReligionRpt]"
                                                                   value="1" <?= in_array("ClsWiseReligionRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="ClsWiseReligionRpt" class="permisnCheck">Class
                                                                Wise Religion Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsWiseCftCreate"
                                                                   name="menuName[ClsWiseCftCreate]"
                                                                   value="1" <?= in_array("ClsWiseCftCreate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="ClsWiseCftCreate" class="permisnCheck">Class
                                                                Wise Certificate Create</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="NewAcaYrStdList"
                                                                   name="menuName[NewAcaYrStdList]"
                                                                   value="1" <?= in_array("NewAcaYrStdList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="NewAcaYrStdList" class="permisnCheck">New
                                                                Academic Year Student List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StdAppLgnDetl"
                                                                   name="menuName[StdAppLgnDetl]"
                                                                   value="1" <?= in_array("StdAppLgnDetl=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="StdAppLgnDetl" class="permisnCheck">Student App
                                                                Login Detail</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DtWseActStdList"
                                                                   name="menuName[DtWseActStdList]"
                                                                   value="1" <?= in_array("DtWseActStdList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="DtWseActStdList" class="permisnCheck">Date Wise
                                                                Active Student List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CnclSlcList"
                                                                   name="menuName[CnclSlcList]"
                                                                   value="1" <?= in_array("CnclSlcList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="CnclSlcList" class="permisnCheck">Cancel Slc
                                                                List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StdBalMatch"
                                                                   name="menuName[StdBalMatch]"
                                                                   value="1" <?= in_array("StdBalMatch=1", $menuData) ? 'checked' : '' ?>
                                                                   class="studentreport"/>
                                                            <label for="StdBalMatch" class="permisnCheck">Student Balance Match</label>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Staff</h4>
                                                            <input type="checkbox" id="staffmasterall"
                                                                   name="menuName[StaffMaster]"
                                                                   value="1" <?= in_array("StaffMaster=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('staffmaster',this.id);"/>
                                                            <label for="staffmasterall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffGender"
                                                                   name="menuName[ManageStaffGender]"
                                                                   value="1" <?= in_array("ManageStaffGender=1", $menuData) ? 'checked' : '' ?>  class="staffmaster"/>
                                                            <label for="ManageStaffGender" class="permisnCheck">Manage   Gender</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffTitle"
                                                                   name="menuName[ManageStaffTitle]"
                                                                   value="1" <?= in_array("ManageStaffTitle=1", $menuData) ? 'checked' : '' ?>  class="staffmaster"/>
                                                            <label for="ManageStaffTitle" class="permisnCheck">Manage   Title</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffType"
                                                                   name="menuName[ManageStaffType]"
                                                                   value="1" <?= in_array("ManageStaffType=1", $menuData) ? 'checked' : '' ?>  class="staffmaster"/>
                                                            <label for="ManageStaffType" class="permisnCheck">Manage   Type</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffDepartment"
                                                                   name="menuName[ManageStaffDepartment]"
                                                                   value="1" <?= in_array("ManageStaffDepartment=1", $menuData) ? 'checked' : '' ?>  class="staffmaster"/>
                                                            <label for="ManageStaffDepartment" class="permisnCheck">Manage   Department</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffTiming"
                                                                   name="menuName[ManageStaffTiming]"
                                                                   value="1" <?= in_array("ManageStaffTiming=1", $menuData) ? 'checked' : '' ?>  class="staffmaster"/>
                                                            <label for="ManageStaffTiming" class="permisnCheck">Manage   Staff Timing</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffSalaryHead"
                                                                   name="menuName[ManageStaffSalaryHead]"
                                                                   value="1" <?= in_array("ManageStaffSalaryHead=1", $menuData) ? 'checked' : '' ?>  class="staffmaster"/>
                                                            <label for="ManageStaffSalaryHead" class="permisnCheck">Manage   Salary Head</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Staff Entry</h4>
                                                            <input type="checkbox" id="staffentrymasterall"
                                                                   name="menuName[StaffEntry]"
                                                                   value="1" <?= in_array("StaffEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('staffentry',this.id);"/>
                                                            <label for="staffentrymasterall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffEntry"
                                                                   name="menuName[ManageStaffEntry]"
                                                                   value="1" <?= in_array("ManageStaffEntry=1", $menuData) ? 'checked' : '' ?>  class="staffentry"/>
                                                            <label for="ManageStaffEntry" class="permisnCheck">Staff Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffGatePass"
                                                                   name="menuName[ManageStaffGatePass]"
                                                                   value="1" <?= in_array("ManageStaffGatePass=1", $menuData) ? 'checked' : '' ?>  class="staffentry"/>
                                                            <label for="ManageStaffGatePass" class="permisnCheck">Staff Gate Pass</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SubjectSettoStaff"
                                                                   name="menuName[SubjectSettoStaff]"
                                                                   value="1" <?= in_array("SubjectSettoStaff=1", $menuData) ? 'checked' : '' ?>  class="staffentry"/>
                                                            <label for="SubjectSettoStaff" class="permisnCheck">Class Wise Subject Assign</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StaffSalaryCal"
                                                                   name="menuName[StaffSalaryCal]"
                                                                   value="1" <?= in_array("StaffSalaryCal=1", $menuData) ? 'checked' : '' ?>  class="staffentry"/>
                                                            <label for="StaffSalaryCal" class="permisnCheck">Staff Salary Calculation</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="WorkingExpCert"
                                                                   name="menuName[WorkingExpCert]"
                                                                   value="1" <?= in_array("WorkingExpCert=1", $menuData) ? 'checked' : '' ?>  class="staffentry"/>
                                                            <label for="WorkingExpCert" class="permisnCheck">Working Experience Certificate</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StaffExpCert"
                                                                   name="menuName[StaffExpCert]"
                                                                   value="1" <?= in_array("StaffExpCert=1", $menuData) ? 'checked' : '' ?>  class="staffentry"/>
                                                            <label for="StaffExpCert" class="permisnCheck">Experience Certificate</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Staff Report</h4>
                                                            <input type="checkbox" id="staffreportall"
                                                                   name="menuName[StaffReport]"
                                                                   value="1" <?= in_array("StaffReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('staffreport',this.id);"/>
                                                            <label for="staffreportall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageStaffList"
                                                                   name="menuName[ManageStaffList]"
                                                                   value="1" <?= in_array("ManageStaffList=1", $menuData) ? 'checked' : '' ?>  class="staffreport"/>
                                                            <label for="ManageStaffList" class="permisnCheck">Staff List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageClsWiseStaffList"
                                                                   name="menuName[ManageClsWiseStaffList]"
                                                                   value="1" <?= in_array("ManageClsWiseStaffList=1", $menuData) ? 'checked' : '' ?>  class="staffreport"/>
                                                            <label for="ManageClsWiseStaffList" class="permisnCheck">Class Wise Staff List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="EstBalReport"
                                                                   name="menuName[EstBalReport]"
                                                                   value="1" <?= in_array("EstBalReport=1", $menuData) ? 'checked' : '' ?>  class="staffreport"/>
                                                            <label for="EstBalReport" class="permisnCheck">Estimate Balance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="HomeWorkReport"
                                                                   name="menuName[HomeWorkReport]"
                                                                   value="1" <?= in_array("HomeWorkReport=1", $menuData) ? 'checked' : '' ?>  class="staffreport"/>
                                                            <label for="HomeWorkReport" class="permisnCheck">Homework Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="HomeWorkAssgnReport"
                                                                   name="menuName[HomeWorkAssgnReport]"
                                                                   value="1" <?= in_array("HomeWorkAssgnReport=1", $menuData) ? 'checked' : '' ?>  class="staffreport"/>
                                                            <label for="HomeWorkAssgnReport" class="permisnCheck">Homework Assign Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StaffSalaryReport"
                                                                   name="menuName[StaffSalaryReport]"
                                                                   value="1" <?= in_array("StaffSalaryReport=1", $menuData) ? 'checked' : '' ?>  class="staffreport"/>
                                                            <label for="StaffSalaryReport" class="permisnCheck">Staff Salary Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StaffPaidSalaryReport"
                                                                   name="menuName[StaffPaidSalaryReport]"
                                                                   value="1" <?= in_array("StaffPaidSalaryReport=1", $menuData) ? 'checked' : '' ?>  class="staffreport"/>
                                                            <label for="StaffPaidSalaryReport" class="permisnCheck">Staff Paid Salary Report</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Fees Master</h4>
                                                            <input type="checkbox" id="feemasterall"
                                                                   name="menuName[FeeMaster]"
                                                                   value="1" <?= in_array("FeeMaster=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('feeMaster',this.id);"/>
                                                            <label for="feemasterall" class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DisCategory"
                                                                   name="menuName[DisCategory]"
                                                                   value="1" <?= in_array("DisCategory=1", $menuData) ? 'checked' : '' ?>  class="feeMaster"/>
                                                            <label for="DisCategory" class="permisnCheck">Discount Category</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="feeGroup"
                                                                   name="menuName[feeGroup]"
                                                                   value="1" <?= in_array("feeGroup=1", $menuData) ? 'checked' : '' ?>  class="feeMaster"/>
                                                            <label for="feeGroup" class="permisnCheck">Manage Fee Group</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="feePlan"
                                                                   name="menuName[feePlan]"
                                                                   value="1" <?= in_array("feePlan=1", $menuData) ? 'checked' : '' ?>  class="feeMaster"/>
                                                            <label for="feePlan" class="permisnCheck">Fee Plan</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Fee Entry</h4>
                                                            <input type="checkbox" id="feeentryall"
                                                                   name="menuName[FeeEntry]"
                                                                   value="1" <?= in_array("FeeEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('feeentry',this.id);"/>
                                                            <label for="feeentryall" class="permisnCheck">ADD</label>
                                                            
                                                            <input type="checkbox" id="selectFeeEntry"
                                                                   onClick="checkUncheck('feeentry',this.id);"/>
                                                            <label for="feeentryall" class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsWiseFeeSet"
                                                                   name="menuName[ClsWiseFeeSet]"
                                                                   value="1" <?= in_array("ClsWiseFeeSet=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="ClsWiseFeeSet" class="permisnCheck">Class Wise
                                                                Fee Set</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="classWiseFeeUnset"
                                                                   name="menuName[classWiseFeeUnset]"
                                                                   value="1" <?= in_array("classWiseFeeUnset=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="classWiseFeeUnset" class="permisnCheck">Class
                                                                Wise Fee Unset</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="classWiseTransportSet"
                                                                   name="menuName[classWiseTransportSet]"
                                                                   value="1" <?= in_array("classWiseTransportSet=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="classWiseTransportSet" class="permisnCheck">Class
                                                                Wise Transport Set</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="classWiseTransportUnset"
                                                                   name="menuName[classWiseTransportUnset]"
                                                                   value="1" <?= in_array("classWiseTransportUnset=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="classWiseTransportUnset" class="permisnCheck">Class
                                                                Wise Transport Unset</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeReceiptMenu"
                                                                   name="menuName[FeeReceiptMenu]"
                                                                   value="1" <?= in_array("FeeReceiptMenu=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="FeeReceiptMenu" class="permisnCheck">Fee
                                                                Receipt</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeReceiptNewMenu"
                                                                   name="menuName[FeeReceiptNewMenu]"
                                                                   value="1" <?= in_array("FeeReceiptNewMenu=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="FeeReceiptNewMenu" class="permisnCheck">Fee
                                                                Receipt New</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FamilyFeeRec"
                                                                   name="menuName[FamilyFeeRec]"
                                                                   value="1" <?= in_array("FamilyFeeRec=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="FamilyFeeRec" class="permisnCheck">Family Fee
                                                                Receipt</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngDemandBill"
                                                                   name="menuName[MngDemandBill]"
                                                                   value="1" <?= in_array("MngDemandBill=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="MngDemandBill" class="permisnCheck">Manage
                                                                Demand Bill</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngChequeList"
                                                                   name="menuName[MngChequeList]"
                                                                   value="1" <?= in_array("MngChequeList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="MngChequeList" class="permisnCheck">Manage
                                                                Cheque List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ECEASReport"
                                                                   name="menuName[ECEASReport]"
                                                                   value="1" <?= in_array("ECEASReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="ECEASReport" class="permisnCheck">ECEAS
                                                                Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="importFees"
                                                                   name="menuName[importFees]"
                                                                   value="1" <?= in_array("importFees=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="importFees" class="permisnCheck">Import Fees</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="importAutoFees"
                                                                   name="menuName[importAutoFees]"
                                                                   value="1" <?= in_array("importAutoFees=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feeentry"/>
                                                            <label for="importAutoFees" class="permisnCheck">Import Auto Fees</label>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">

                                                            <h4 class="col-md-2">Fee Report</h4>
                                                            <input type="checkbox" id="feereportall"
                                                                   name="menuName[FeeReport]"
                                                                   value="1" <?= in_array("FeeReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('feereport',this.id);"/>
                                                            <label for="feereportall" class="permisnCheck">ADD</label>

                                                            <input type="checkbox" id="selectFee"
                                                                   onClick="checkUncheck('feereport',this.id);"/>
                                                            <label for="feereportall" class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FDBDateWise"
                                                                   name="menuName[FDBDateWise]"
                                                                   value="1" <?= in_array("FDBDateWise=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FDBDateWise" class="permisnCheck">Fee Day Book
                                                                Date Wise</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FDBDateWseWthPreDis"
                                                                   name="menuName[FDBDateWseWthPreDis]"
                                                                   value="1" <?= in_array("FDBDateWseWthPreDis=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FDBDateWseWthPreDis" class="permisnCheck">Fee
                                                                Day Book Date Wise Pre Discount</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FDBWthRegFee"
                                                                   name="menuName[FDBWthRegFee]"
                                                                   value="1" <?= in_array("FDBWthRegFee=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FDBWthRegFee" class="permisnCheck">Fee Day Book
                                                                With Registration Fee</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FndRecFDB"
                                                                   name="menuName[FndRecFDB]"
                                                                   value="1" <?= in_array("FndRecFDB=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FndRecFDB" class="permisnCheck">Fund Received
                                                                Fee Day Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeRecDayBook"
                                                                   name="menuName[FeeRecDayBook]"
                                                                   value="1" <?= in_array("FeeRecDayBook=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FeeRecDayBook" class="permisnCheck">Fee Received
                                                                Day Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FmlyFDBDWise"
                                                                   name="menuName[FmlyFDBDWise]"
                                                                   value="1" <?= in_array("FmlyFDBDWise=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FmlyFDBDWise" class="permisnCheck">Family Fee
                                                                Day Book Date Wise</label>
                                                        </div>


                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeCollectionReport"
                                                                   name="menuName[FeeCollectionReport]"
                                                                   value="1" <?= in_array("FeeCollectionReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FeeCollectionReport" class="permisnCheck">Fee
                                                                Collection Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeCollectionReportOnly"
                                                                   name="menuName[FeeCollectionReportOnly]"
                                                                   value="1" <?= in_array("FeeCollectionReportOnly=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FeeCollectionReportOnly" class="permisnCheck">Fee
                                                                Collection Report Only</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeColRptOnlyWithDis"
                                                                   name="menuName[FeeColRptOnlyWithDis]"
                                                                   value="1" <?= in_array("FeeColRptOnlyWithDis=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FeeColRptOnlyWithDis" class="permisnCheck">Fee
                                                                Coll. Rpt. Only(With Discount)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="HostelCollectionReport"
                                                                   name="menuName[HostelCollectionReport]"
                                                                   value="1" <?= in_array("HostelCollectionReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="HostelCollectionReport" class="permisnCheck">Hostel
                                                                Collection Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="UserWiseReceivedReport"
                                                                   name="menuName[UserWiseReceivedReport]"
                                                                   value="1" <?= in_array("UserWiseReceivedReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="UserWiseReceivedReport" class="permisnCheck">User
                                                                Wise Received Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DuesList"
                                                                   name="menuName[DuesList]"
                                                                   value="1" <?= in_array("DuesList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="DuesList" class="permisnCheck">Dues List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DuesList2"
                                                                   name="menuName[DuesList2]"
                                                                   value="1" <?= in_array("DuesList2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="DuesList2" class="permisnCheck">Dues List-2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DuesListRecAmount"
                                                                   name="menuName[DuesListRecAmount]"
                                                                   value="1" <?= in_array("DuesListRecAmount=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="DuesListRecAmount" class="permisnCheck">Dues List with All Rec. Amount</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FamilyDuesList"
                                                                   name="menuName[FamilyDuesList]"
                                                                   value="1" <?= in_array("FamilyDuesList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FamilyDuesList" class="permisnCheck">Family Dues List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DuesListQueueWise"
                                                                   name="menuName[DuesListQueueWise]"
                                                                   value="1" <?= in_array("DuesListQueueWise=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="DuesListQueueWise" class="permisnCheck">Dues List Queue Wise</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FundWiseDuesList"
                                                                   name="menuName[FundWiseDuesList]"
                                                                   value="1" <?= in_array("FundWiseDuesList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FundWiseDuesList" class="permisnCheck">Fund Wise
                                                                Dues List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SelectedFundWiseDuesList"
                                                                   name="menuName[SelectedFundWiseDuesList]"
                                                                   value="1" <?= in_array("SelectedFundWiseDuesList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="SelectedFundWiseDuesList" class="permisnCheck">Selected
                                                                Fund Wise Dues List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeSchedule"
                                                                   name="menuName[FeeSchedule]"
                                                                   value="1" <?= in_array("FeeSchedule=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FeeSchedule" class="permisnCheck">Fee
                                                                Schedule</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="YearlyFeeTransactionReport"
                                                                   name="menuName[YearlyFeeTransactionReport]"
                                                                   value="1" <?= in_array("YearlyFeeTransactionReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="YearlyFeeTransactionReport"
                                                                   class="permisnCheck">Yearly Fee Transaction
                                                                Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="YearlyTransportTransactionReport"
                                                                   name="menuName[YearlyTransportTransactionReport]"
                                                                   value="1" <?= in_array("YearlyTransportTransactionReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="YearlyTransportTransactionReport"
                                                                   class="permisnCheck">Yearly Transport Transaction
                                                                Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StudentWiseFeeTransactionReport"
                                                                   name="menuName[StudentWiseFeeTransactionReport]"
                                                                   value="1" <?= in_array("StudentWiseFeeTransactionReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="StudentWiseFeeTransactionReport"
                                                                   class="permisnCheck">Student Wise Fee Transaction
                                                                Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClassWiseOldBalanceReport"
                                                                   name="menuName[ClassWiseOldBalanceReport]"
                                                                   value="1" <?= in_array("ClassWiseOldBalanceReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="ClassWiseOldBalanceReport" class="permisnCheck">Class
                                                                Wise Old Balance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClassWiseOldBalanceReport2"
                                                                   name="menuName[ClassWiseOldBalanceReport2]"
                                                                   value="1" <?= in_array("ClassWiseOldBalanceReport2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="ClassWiseOldBalanceReport2" class="permisnCheck">Class
                                                                Wise Old Balance Report-2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClassWiseTransactionReport"
                                                                   name="menuName[ClassWiseTransactionReport]"
                                                                   value="1" <?= in_array("ClassWiseTransactionReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="ClassWiseTransactionReport"
                                                                   class="permisnCheck">Class Wise Transaction
                                                                Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="InsWiseTrnsRpt"
                                                                   name="menuName[InsWiseTrnsRpt]"
                                                                   value="1" <?= in_array("InsWiseTrnsRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="InsWiseTrnsRpt" class="permisnCheck">Installment
                                                                Wise Transaction Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DateWiseTrnsRpt"
                                                                   name="menuName[DateWiseTrnsRpt]"
                                                                   value="1" <?= in_array("DateWiseTrnsRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="DateWiseTrnsRpt" class="permisnCheck">Date Wise
                                                                Transaction Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClassWiseDistRptt"
                                                                   name="menuName[ClassWiseDistRptt]"
                                                                   value="1" <?= in_array("ClassWiseDistRptt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="ClassWiseDistRptt" class="permisnCheck">Class
                                                                Wise Discount Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FundWiseDistRpt"
                                                                   name="menuName[FundWiseDistRpt]"
                                                                   value="1" <?= in_array("FundWiseDistRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FundWiseDistRpt" class="permisnCheck">Fund Wise
                                                                Discount Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FundWiseAdmTmDisRpt"
                                                                   name="menuName[FundWiseAdmTmDisRpt]"
                                                                   value="1" <?= in_array("FundWiseAdmTmDisRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FundWiseAdmTmDisRpt" class="permisnCheck">Fund
                                                                Wise Adm. Time Discount Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsWiseFamBalRpt"
                                                                   name="menuName[ClsWiseFamBalRpt]"
                                                                   value="1" <?= in_array("ClsWiseFamBalRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="ClsWiseFamBalRpt" class="permisnCheck">Class
                                                                Wise Family Balance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="EstimationRpt"
                                                                   name="menuName[EstimationRpt]"
                                                                   value="1" <?= in_array("EstimationRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="EstimationRpt" class="permisnCheck">Estimation
                                                                Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeCard" name="menuName[FeeCard]"
                                                                   value="1" <?= in_array("FeeCard=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FeeCard" class="permisnCheck">Fee Card</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DailyFundRecRpt"
                                                                   name="menuName[DailyFundRecRpt]"
                                                                   value="1" <?= in_array("DailyFundRecRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="DailyFundRecRpt" class="permisnCheck">Daily Fund
                                                                Received Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="PayModWiseRecRpt"
                                                                   name="menuName[PayModWiseRecRpt]"
                                                                   value="1" <?= in_array("PayModWiseRecRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="PayModWiseRecRpt" class="permisnCheck">Payment
                                                                Mode Wise Received Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DisWiseRecRpt"
                                                                   name="menuName[DisWiseRecRpt]"
                                                                   value="1" <?= in_array("DisWiseRecRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="DisWiseRecRpt" class="permisnCheck">Discount
                                                                Wise Received Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CanFeeRecRpt"
                                                                   name="menuName[CanFeeRecRpt]"
                                                                   value="1" <?= in_array("CanFeeRecRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="CanFeeRecRpt" class="permisnCheck">Canceled Fee
                                                                Receipt Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="OnlinePaymentRpt"
                                                                   name="menuName[OnlinePaymentRpt]"
                                                                   value="1" <?= in_array("OnlinePaymentRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="OnlinePaymentRpt" class="permisnCheck">Direct
                                                                Online Payment Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AnnIncRpt"
                                                                   name="menuName[AnnIncRpt]"
                                                                   value="1" <?= in_array("AnnIncRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="AnnIncRpt" class="permisnCheck">Annual Income
                                                                Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FineRpt"
                                                                   name="menuName[FineRpt]"
                                                                   value="1" <?= in_array("FineRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FineRpt" class="permisnCheck">Fine Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClassInstallRpt"
                                                                   name="menuName[ClassInstallRpt]"
                                                                   value="1" <?= in_array("ClassInstallRpt=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="ClassInstallRpt" class="permisnCheck">Class wise Installment Detail Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StudentLedger"
                                                                   name="menuName[StudentLedger]"
                                                                   value="1" <?= in_array("StudentLedger=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="StudentLedger" class="permisnCheck">Student Ledger</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FeeRemRmdrList"
                                                                   name="menuName[FeeRemRmdrList]"
                                                                   value="1" <?= in_array("FeeRemRmdrList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="FeeRemRmdrList" class="permisnCheck">Fee Remarks Reminder List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsWiseassignFeeReport"
                                                                   name="menuName[ClsWiseassignFeeReport]"
                                                                   value="1" <?= in_array("ClsWiseassignFeeReport=1", $menuData) ? 'checked' : '' ?>
                                                                   class="feereport"/>
                                                            <label for="ClsWiseassignFeeReport" class="permisnCheck">Class Wise Fee Assign Report</label>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Account</h4>
                                                            <input type="checkbox" id="accountmasterall"
                                                                   name="menuName[AccountMaster]"
                                                                   value="1" <?= in_array("AccountMaster=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('accountmaster',this.id);"/>
                                                            <label for="accountmasterall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManageAccountGroup"
                                                                   name="menuName[ManageAccountGroup]"
                                                                   value="1" <?= in_array("ManageAccountGroup=1", $menuData) ? 'checked' : '' ?>  class="accountmaster"/>
                                                            <label for="ManageAccountGroup" class="permisnCheck">Account Group</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AccSubGrpType"
                                                                   name="menuName[AccSubGrpType]"
                                                                   value="1" <?= in_array("AccSubGrpType=1", $menuData) ? 'checked' : '' ?>  class="accountmaster"/>
                                                            <label for="AccSubGrpType" class="permisnCheck">Account Sub Group Type</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Account Entry</h4>
                                                            <input type="checkbox" id="accountentryall"
                                                                   name="menuName[AccountEntry]"
                                                                   value="1" <?= in_array("AccountEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('accountentry',this.id);"/>
                                                            <label for="accountentryall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CreateAccount"
                                                                   name="menuName[CreateAccount]"
                                                                   value="1" <?= in_array("CreateAccount=1", $menuData) ? 'checked' : '' ?>  class="accountentry"/>
                                                            <label for="CreateAccount" class="permisnCheck">Account Group</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="PaymentEntry"
                                                                   name="menuName[PaymentEntry]"
                                                                   value="1" <?= in_array("PaymentEntry=1", $menuData) ? 'checked' : '' ?>  class="accountentry"/>
                                                            <label for="PaymentEntry" class="permisnCheck">Payment</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ReceiptEntry"
                                                                   name="menuName[ReceiptEntry]"
                                                                   value="1" <?= in_array("ReceiptEntry=1", $menuData) ? 'checked' : '' ?>  class="accountentry"/>
                                                            <label for="ReceiptEntry" class="permisnCheck">Receipt</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ContraEntry"
                                                                   name="menuName[ContraEntry]"
                                                                   value="1" <?= in_array("ContraEntry=1", $menuData) ? 'checked' : '' ?>  class="accountentry"/>
                                                            <label for="ContraEntry" class="permisnCheck">Contra</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="JournalEntry"
                                                                   name="menuName[JournalEntry]"
                                                                   value="1" <?= in_array("JournalEntry=1", $menuData) ? 'checked' : '' ?>  class="accountentry"/>
                                                            <label for="JournalEntry" class="permisnCheck">Journal</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Account Report</h4>
                                                            <input type="checkbox" id="accountreportall"
                                                                   name="menuName[AccountReport]"
                                                                   value="1" <?= in_array("AccountReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('accountreport',this.id);"/>
                                                            <label for="accountreportall"
                                                                   class="permisnCheck">ADD</label>

                                                            <input type="checkbox" id="selectAccount"
                                                                  onClick="checkUncheck('accountreport',this.id);"/>
                                                            <label for="accountreportall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="DayBookRpt"
                                                                   name="menuName[DayBookRpt]"
                                                                   value="1" <?= in_array("DayBookRpt=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="DayBookRpt" class="permisnCheck">Day Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CashBookRpt"
                                                                   name="menuName[CashBookRpt]"
                                                                   value="1" <?= in_array("CashBookRpt=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="CashBookRpt" class="permisnCheck">Cash Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="PayRecDayBook"
                                                                   name="menuName[PayRecDayBook]"
                                                                   value="1" <?= in_array("PayRecDayBook=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="PayRecDayBook" class="permisnCheck">Payment Receipt Day Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="PayRecVoucherDayBook"
                                                                   name="menuName[PayRecVoucherDayBook]"
                                                                   value="1" <?= in_array("PayRecVoucherDayBook=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="PayRecVoucherDayBook" class="permisnCheck">Payment Receipt Voucher Day Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LedgerRpt"
                                                                   name="menuName[LedgerRpt]"
                                                                   value="1" <?= in_array("LedgerRpt=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="LedgerRpt" class="permisnCheck">Ledger</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LedgerSummary"
                                                                   name="menuName[LedgerSummary]"
                                                                   value="1" <?= in_array("LedgerSummary=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="LedgerSummary" class="permisnCheck">Ledger Summary</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="OutStandAnalysis"
                                                                   name="menuName[OutStandAnalysis]"
                                                                   value="1" <?= in_array("OutStandAnalysis=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="OutStandAnalysis" class="permisnCheck">Outstanding Analysis</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TrialBalanceRpt"
                                                                   name="menuName[TrialBalanceRpt]"
                                                                   value="1" <?= in_array("TrialBalanceRpt=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="TrialBalanceRpt" class="permisnCheck">Trial Balance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TrialBalanceSubGroupWiseRpt"
                                                                   name="menuName[TrialBalanceSubGroupWiseRpt]"
                                                                   value="1" <?= in_array("TrialBalanceSubGroupWiseRpt=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="TrialBalanceSubGroupWiseRpt" class="permisnCheck">Sub Group Wise Trial Balance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ProfitandLoss"
                                                                   name="menuName[ProfitandLoss]"
                                                                   value="1" <?= in_array("ProfitandLoss=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="ProfitandLoss" class="permisnCheck">Profit And Loss</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BalSheetRpt"
                                                                   name="menuName[BalSheetRpt]"
                                                                   value="1" <?= in_array("BalSheetRpt=1", $menuData) ? 'checked' : '' ?>  class="accountreport"/>
                                                            <label for="BalSheetRpt" class="permisnCheck">Balance Sheet</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Stock</h4>
                                                            <input type="checkbox" id="stockmasterall"
                                                                   name="menuName[StockMaster]"
                                                                   value="1" <?= in_array("StockMaster=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('stockmaster',this.id);"/>
                                                            <label for="stockmasterall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SetTaxValue"
                                                                   name="menuName[SetTaxValue]"
                                                                   value="1" <?= in_array("SetTaxValue=1", $menuData) ? 'checked' : '' ?>  class="stockmaster"/>
                                                            <label for="SetTaxValue" class="permisnCheck">Set Tax Value</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockCompany"
                                                                   name="menuName[StockCompany]"
                                                                   value="1" <?= in_array("StockCompany=1", $menuData) ? 'checked' : '' ?>  class="stockmaster"/>
                                                            <label for="StockCompany" class="permisnCheck">Create Compnay</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockCategory"
                                                                   name="menuName[StockCategory]"
                                                                   value="1" <?= in_array("StockCategory=1", $menuData) ? 'checked' : '' ?>  class="stockmaster"/>
                                                            <label for="StockCategory" class="permisnCheck">Create Category</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockItem"
                                                                   name="menuName[StockItem]"
                                                                   value="1" <?= in_array("StockItem=1", $menuData) ? 'checked' : '' ?>  class="stockmaster"/>
                                                            <label for="StockItem" class="permisnCheck">Create Item</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SetMaterial"
                                                                   name="menuName[SetMaterial]"
                                                                   value="1" <?= in_array("SetMaterial=1", $menuData) ? 'checked' : '' ?>  class="stockmaster"/>
                                                            <label for="SetMaterial" class="permisnCheck">Set Material</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Stock Entry</h4>
                                                            <input type="checkbox" id="stockentryall"
                                                                   name="menuName[StockEntry]"
                                                                   value="1" <?= in_array("StockEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('stockEntry',this.id);"/>
                                                            <label for="stockentryall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockPurItem"
                                                                   name="menuName[StockPurItem]"
                                                                   value="1" <?= in_array("StockPurItem=1", $menuData) ? 'checked' : '' ?>  class="stockEntry"/>
                                                            <label for="StockPurItem" class="permisnCheck">Purchase Item</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockSaleItem"
                                                                   name="menuName[StockSaleItem]"
                                                                   value="1" <?= in_array("StockSaleItem=1", $menuData) ? 'checked' : '' ?>  class="stockEntry"/>
                                                            <label for="StockSaleItem" class="permisnCheck">Sale Item</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockPurReturn"
                                                                   name="menuName[StockPurReturn]"
                                                                   value="1" <?= in_array("StockPurReturn=1", $menuData) ? 'checked' : '' ?>  class="stockEntry"/>
                                                            <label for="StockPurReturn" class="permisnCheck">Purchase Return</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockSaleReturn"
                                                                   name="menuName[StockSaleReturn]"
                                                                   value="1" <?= in_array("StockSaleReturn=1", $menuData) ? 'checked' : '' ?>  class="stockEntry"/>
                                                            <label for="StockSaleReturn" class="permisnCheck">Sale Return</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockSaleIssue"
                                                                   name="menuName[StockSaleIssue]"
                                                                   value="1" <?= in_array("StockSaleIssue=1", $menuData) ? 'checked' : '' ?>  class="stockEntry"/>
                                                            <label for="StockSaleIssue" class="permisnCheck">Sale Issue</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockSaleIssueReturn"
                                                                   name="menuName[StockSaleIssueReturn]"
                                                                   value="1" <?= in_array("StockSaleIssueReturn=1", $menuData) ? 'checked' : '' ?>  class="stockEntry"/>
                                                            <label for="StockSaleIssueReturn" class="permisnCheck">Sale Issue Return</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Stock Report</h4>
                                                            <input type="checkbox" id="stockreportall"
                                                                   name="menuName[StockReport]"
                                                                   value="1" <?= in_array("StockReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('stockReport',this.id);"/>
                                                            <label for="stockreportall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockIssueReturnRegister"
                                                                   name="menuName[StockIssueReturnRegister]"
                                                                   value="1" <?= in_array("StockIssueReturnRegister=1", $menuData) ? 'checked' : '' ?>  class="stockReport"/>
                                                            <label for="StockIssueReturnRegister" class="permisnCheck">Stock Issue Return Register</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StockRegister"
                                                                   name="menuName[StockRegister]"
                                                                   value="1" <?= in_array("StockRegister=1", $menuData) ? 'checked' : '' ?>  class="stockReport"/>
                                                            <label for="StockRegister" class="permisnCheck">Stock Register</label>
                                                        </div>


                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Attendance Entry</h4>
                                                            <input type="checkbox" id="attendancereportall"
                                                                   name="menuName[AttendanceReport]"
                                                                   value="1" <?= in_array("AttendanceReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('attendanceReport',this.id);"/>
                                                            <label for="attendancereportall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AttendanceHead"
                                                                   name="menuName[AttendanceHead]"
                                                                   value="1" <?= in_array("AttendanceHead=1", $menuData) ? 'checked' : '' ?>  class="attendanceReport"/>
                                                            <label for="AttendanceHead" class="permisnCheck">Attendance Head</label>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Student Attendance</h4>
                                                            <input type="checkbox" id="studentattendancereportall"
                                                                   name="menuName[StuAtnReport]"
                                                                   value="1" <?= in_array("StuAtnReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('stuAtnReport',this.id);"/>
                                                            <label for="studentattendancereportall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StuBioAtt"
                                                                   name="menuName[StuBioAtt]"
                                                                   value="1" <?= in_array("StuBioAtt=1", $menuData) ? 'checked' : '' ?>  class="stuAtnReport"/>
                                                            <label for="StuBioAtt" class="permisnCheck">Student Biometric Attendance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StuDateWiseAtt"
                                                                   name="menuName[StuDateWiseAtt]"
                                                                   value="1" <?= in_array("StuDateWiseAtt=1", $menuData) ? 'checked' : '' ?>  class="stuAtnReport"/>
                                                            <label for="StuDateWiseAtt" class="permisnCheck">Student DateWise Attendance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="setHoliday"
                                                                   name="menuName[setHoliday]"
                                                                   value="1" <?= in_array("setHoliday=1", $menuData) ? 'checked' : '' ?>  class="stuAtnReport"/>
                                                            <label for="setHoliday" class="permisnCheck">Set Holiday for Student</label>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2"> Staff Attendance</h4>
                                                            <input type="checkbox" id="staffattreportall"
                                                                   name="menuName[StffAtnReport]"
                                                                   value="1" <?= in_array("StffAtnReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('stffAtnReport',this.id);"/>
                                                            <label for="staffattreportall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StffDateWiseAtt"
                                                                   name="menuName[StffDateWiseAtt]"
                                                                   value="1" <?= in_array("StffDateWiseAtt=1", $menuData) ? 'checked' : '' ?>  class="stffAtnReport"/>
                                                            <label for="StffDateWiseAtt" class="permisnCheck">Staff Datewise Attendance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StffBulktt"
                                                                   name="menuName[StffBulktt]"
                                                                   value="1" <?= in_array("StffBulktt=1", $menuData) ? 'checked' : '' ?>  class="stffAtnReport"/>
                                                            <label for="StffBulktt" class="permisnCheck">Staff Bulk Attendance</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StffImportAtt"
                                                                   name="menuName[StffImportAtt]"
                                                                   value="1" <?= in_array("StffImportAtt=1", $menuData) ? 'checked' : '' ?>  class="stffAtnReport"/>
                                                            <label for="StffImportAtt" class="permisnCheck">Import Staff Attendance From Excel</label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Attendance Report</h4>
                                                            <input type="checkbox" id="attreportall"
                                                                   name="menuName[AttReport]"
                                                                   value="1" <?= in_array("AttReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('attReport',this.id);"/>
                                                            <label for="attreportall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StuBulkAttRpt"
                                                                   name="menuName[StuBulkAttRpt]"
                                                                   value="1" <?= in_array("StuBulkAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="StuBulkAttRpt" class="permisnCheck">Student Bulk Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StffBulkAttRpt"
                                                                   name="menuName[StffBulkAttRpt]"
                                                                   value="1" <?= in_array("StffBulkAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="StffBulkAttRpt" class="permisnCheck">Staff Bulk Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StdTotAttRpt"
                                                                   name="menuName[StdTotAttRpt]"
                                                                   value="1" <?= in_array("StdTotAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="StdTotAttRpt" class="permisnCheck">Student Total Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StfTotalAttRpt"
                                                                   name="menuName[StfTotalAttRpt]"
                                                                   value="1" <?= in_array("StfTotalAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="StfTotalAttRpt" class="permisnCheck">Staff Total Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StatusWiseAttRpt"
                                                                   name="menuName[StatusWiseAttRpt]"
                                                                   value="1" <?= in_array("StatusWiseAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="StatusWiseAttRpt" class="permisnCheck">Status Wise Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="RegAbsntAttRpt"
                                                                   name="menuName[RegAbsntAttRpt]"
                                                                   value="1" <?= in_array("RegAbsntAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="RegAbsntAttRpt" class="permisnCheck">Regular Absent Report</label>
                                                        </div>

                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Transport</h4>
                                                            <input type="checkbox" id="transportMasterall"
                                                                   name="menuName[TransportMaster]"
                                                                   value="1" <?= in_array("TransportMaster=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('transportMaster',this.id);"/>
                                                            <label for="transportMasterall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngBusno"
                                                                   name="menuName[MngBusno]"
                                                                   value="1" <?= in_array("MngBusno=1", $menuData) ? 'checked' : '' ?>  class="transportMaster"/>
                                                            <label for="MngBusno" class="permisnCheck">Manage Bus No</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngBusCapacity"
                                                                   name="menuName[MngBusCapacity]"
                                                                   value="1" <?= in_array("MngBusCapacity=1", $menuData) ? 'checked' : '' ?>  class="transportMaster"/>
                                                            <label for="MngBusCapacity" class="permisnCheck">Manage Bus Capacity</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngStopage"
                                                                   name="menuName[MngStopage]"
                                                                   value="1" <?= in_array("MngStopage=1", $menuData) ? 'checked' : '' ?>  class="transportMaster"/>
                                                            <label for="MngStopage" class="permisnCheck">Manage Stopage</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngRoute"
                                                                   name="menuName[MngRoute]"
                                                                   value="1" <?= in_array("MngRoute=1", $menuData) ? 'checked' : '' ?>  class="transportMaster"/>
                                                            <label for="MngRoute" class="permisnCheck">Manage Route</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TransportTimePeriod"
                                                                   name="menuName[TransportTimePeriod]"
                                                                   value="1" <?= in_array("TransportTimePeriod=1", $menuData) ? 'checked' : '' ?>  class="transportMaster"/>
                                                            <label for="TransportTimePeriod" class="permisnCheck">Time Period For Trans. Install.</label>
                                                        </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Transport Entry</h4>
                                                            <input type="checkbox" id="transportEntryall"
                                                                   name="menuName[TransportEntry]"
                                                                   value="1" <?= in_array("TransportEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('transportEntry',this.id);"/>
                                                            <label for="transportEntryall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngRoutrPlan"
                                                                   name="menuName[MngRoutrPlan]"
                                                                   value="1" <?= in_array("MngRoutrPlan=1", $menuData) ? 'checked' : '' ?>  class="transportEntry"/>
                                                            <label for="MngRoutrPlan" class="permisnCheck">Manage Route Plan</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FuelEntry"
                                                                   name="menuName[FuelEntry]"
                                                                   value="1" <?= in_array("FuelEntry=1", $menuData) ? 'checked' : '' ?>  class="transportEntry"/>
                                                            <label for="FuelEntry" class="permisnCheck">Fuel Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MeterReadingEntry"
                                                                   name="menuName[MeterReadingEntry]"
                                                                   value="1" <?= in_array("MeterReadingEntry=1", $menuData) ? 'checked' : '' ?>  class="transportEntry"/>
                                                            <label for="MeterReadingEntry" class="permisnCheck">Daily Meter Reading Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BusWiseAtt"
                                                                   name="menuName[BusWiseAtt]"
                                                                   value="1" <?= in_array("BusWiseAtt=1", $menuData) ? 'checked' : '' ?>  class="stuAtnReport"/>
                                                            <label for="BusWiseAtt" class="permisnCheck">Transport Buswise Attendance</label>
                                                        </div>

                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Transport Report</h4>
                                                            <input type="checkbox" id="transportReportall"
                                                                   name="menuName[TransportReport]"
                                                                   value="1" <?= in_array("TransportReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('transportReport',this.id);"/>
                                                            <label for="transportReportall"
                                                                   class="permisnCheck">ADD</label>
                                                            
                                                            <input type="checkbox" id="selectTransport"
                                                                   onClick="checkUncheck('transportReport',this.id);"/>
                                                            <label for="transportReportall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TransFeeDayBook"
                                                                   name="menuName[TransFeeDayBook]"
                                                                   value="1" <?= in_array("TransFeeDayBook=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="TransFeeDayBook" class="permisnCheck">Transport-Fee Day Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StopageListRpt"
                                                                   name="menuName[StopageListRpt]"
                                                                   value="1" <?= in_array("StopageListRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="StopageListRpt" class="permisnCheck">Stopage List Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BusStrengthRpt"
                                                                   name="menuName[BusStrengthRpt]"
                                                                   value="1" <?= in_array("BusStrengthRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="BusStrengthRpt" class="permisnCheck">Bus Wise Strength Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StopageStrengthRpt"
                                                                   name="menuName[StopageStrengthRpt]"
                                                                   value="1" <?= in_array("StopageStrengthRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="StopageStrengthRpt" class="permisnCheck">Stopage Wise Strength/Collection Rpt</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SpgClsWiseStrengthRpt"
                                                                   name="menuName[SpgClsWiseStrengthRpt]"
                                                                   value="1" <?= in_array("SpgClsWiseStrengthRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="SpgClsWiseStrengthRpt" class="permisnCheck">Stopage/Class Wise Strength Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BusClsWiseStrengthRpt"
                                                                   name="menuName[BusClsWiseStrengthRpt]"
                                                                   value="1" <?= in_array("BusClsWiseStrengthRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="BusClsWiseStrengthRpt" class="permisnCheck">Bus/Class Wise Strength Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="RouteWiseStrengthRpt"
                                                                   name="menuName[RouteWiseStrengthRpt]"
                                                                   value="1" <?= in_array("RouteWiseStrengthRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="RouteWiseStrengthRpt" class="permisnCheck">Route Wise Strength Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TransDetailRpt"
                                                                   name="menuName[TransDetailRpt]"
                                                                   value="1" <?= in_array("TransDetailRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="TransDetailRpt" class="permisnCheck">Transport Detail Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FuelRpt"
                                                                   name="menuName[FuelRpt]"
                                                                   value="1" <?= in_array("FuelRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="FuelRpt" class="permisnCheck">Fuel Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BuswseStdRpt"
                                                                   name="menuName[BuswseStdRpt]"
                                                                   value="1" <?= in_array("BuswseStdRpt=1", $menuData) ? 'checked' : '' ?>  class="transportReport"/>
                                                            <label for="BuswseStdRpt" class="permisnCheck">Bus Wise Student Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="VanAttRpt"
                                                                   name="menuName[VanAttRpt]"
                                                                   value="1" <?= in_array("VanAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="VanAttRpt" class="permisnCheck">Van Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BusWiseAttRpt"
                                                                   name="menuName[BusWiseAttRpt]"
                                                                   value="1" <?= in_array("BusWiseAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="BusWiseAttRpt" class="permisnCheck">Bus Wise Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BusWiseTotAttRpt"
                                                                   name="menuName[BusWiseTotAttRpt]"
                                                                   value="1" <?= in_array("BusWiseTotAttRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="BusWiseTotAttRpt" class="permisnCheck">Bus Wise Total Attendance Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BusWiseCollectionRpt"
                                                                   name="menuName[BusWiseCollectionRpt]"
                                                                   value="1" <?= in_array("BusWiseCollectionRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="BusWiseCollectionRpt" class="permisnCheck">Bus Wise Collection Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BusWiseCapacityRpt"
                                                                   name="menuName[BusWiseCapacityRpt]"
                                                                   value="1" <?= in_array("BusWiseCapacityRpt=1", $menuData) ? 'checked' : '' ?>  class="attReport"/>
                                                            <label for="BusWiseCapacityRpt" class="permisnCheck">Bus Wise Capacity Report</label>
                                                        </div>


                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Examination Master</h4>
                                                            <input type="checkbox" id="examinationmastertall"
                                                                   name="menuName[ExaminationMaster]"
                                                                   value="1" <?= in_array("ExaminationMaster=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('examinationMaster',this.id);"/>
                                                            <label for="examinationmastertall"
                                                                   class="permisnCheck">ADD</label>
                                                            
                                                            <input type="checkbox" id="selectExamination"
                                                                    onClick="checkUncheck('examinationMaster',this.id);"/>
                                                            <label for="examinationmastertall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngTermName"
                                                                   name="menuName[MngTermName]"
                                                                   value="1" <?= in_array("MngTermName=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="MngTermName" class="permisnCheck">Manage Term Name</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngAssesment"
                                                                   name="menuName[MngAssesment]"
                                                                   value="1" <?= in_array("MngAssesment=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="MngAssesment" class="permisnCheck">Manage Assesment</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngActivity"
                                                                   name="menuName[MngActivity]"
                                                                   value="1" <?= in_array("MngActivity=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="MngActivity" class="permisnCheck">Manage Activity</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngSkillActivity"
                                                                   name="menuName[MngSkillActivity]"
                                                                   value="1" <?= in_array("MngSkillActivity=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="MngSkillActivity" class="permisnCheck">Manage Skill Activity</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AddInfo"
                                                                   name="menuName[AddInfo]"
                                                                   value="1" <?= in_array("AddInfo=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="AddInfo" class="permisnCheck">Add Additional Info</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AddInfoSubType"
                                                                   name="menuName[AddInfoSubType]"
                                                                   value="1" <?= in_array("AddInfoSubType=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="AddInfoSubType" class="permisnCheck">Add Additional Info Sub Type</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SetAddInfoGrade"
                                                                   name="menuName[SetAddInfoGrade]"
                                                                   value="1" <?= in_array("SetAddInfoGrade=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="SetAddInfoGrade" class="permisnCheck">Set Additional Info Grade</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClsTchRmrks"
                                                                   name="menuName[ClsTchRmrks]"
                                                                   value="1" <?= in_array("ClsTchRmrks=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="ClsTchRmrks" class="permisnCheck">Class Teacher Remarks</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="stdSpclInfo"
                                                                   name="menuName[stdSpclInfo]"
                                                                   value="1" <?= in_array("stdSpclInfo=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="stdSpclInfo" class="permisnCheck">Student Special Info</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="PrincipalSign"
                                                                   name="menuName[PrincipalSign]"
                                                                   value="1" <?= in_array("PrincipalSign=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="PrincipalSign" class="permisnCheck">Principal Signature Name</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClaWiseRptCard"
                                                                   name="menuName[ClaWiseRptCard]"
                                                                   value="1" <?= in_array("ClaWiseRptCard=1", $menuData) ? 'checked' : '' ?>  class="examinationMaster"/>
                                                            <label for="ClaWiseRptCard" class="permisnCheck">Manage Class Wise Report Card</label>
                                                        </div>



                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Examination Entry</h4>
                                                            <input type="checkbox" id="examinationentryall"
                                                                   name="menuName[ExaminationEntry]"
                                                                   value="1" <?= in_array("ExaminationEntry=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('examinationEntry',this.id);"/>
                                                            <label for="examinationentryall"
                                                                   class="permisnCheck">ADD</label>
                                                            
                                                            <input type="checkbox" id="selectExaminationEntry"
                                                                   onClick="checkUncheck('examinationEntry',this.id);"/>
                                                            <label for="examinationentryall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ActWiseMrks"
                                                                   name="menuName[ActWiseMrks]"
                                                                   value="1" <?= in_array("ActWiseMrks=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="ActWiseMrks" class="permisnCheck">Set Activity Wise Marks</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MrksEntry"
                                                                   name="menuName[MrksEntry]"
                                                                   value="1" <?= in_array("MrksEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="MrksEntry" class="permisnCheck">Marks Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="GradingSystem"
                                                                   name="menuName[GradingSystem]"
                                                                   value="1" <?= in_array("GradingSystem=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="GradingSystem" class="permisnCheck">Grading System</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MiscSubWiseMaxMrks"
                                                                   name="menuName[MiscSubWiseMaxMrks]"
                                                                   value="1" <?= in_array("MiscSubWiseMaxMrks=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="MiscSubWiseMaxMrks" class="permisnCheck">Misc Subject Wise Max Marks</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AddSubWiseMaxMrks"
                                                                   name="menuName[AddSubWiseMaxMrks]"
                                                                   value="1" <?= in_array("AddSubWiseMaxMrks=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="AddSubWiseMaxMrks" class="permisnCheck">Additional Subject Wise Max Marks</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MiscMarkEntry"
                                                                   name="menuName[MiscMarkEntry]"
                                                                   value="1" <?= in_array("MiscMarkEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="MiscMarkEntry" class="permisnCheck">Marks Entry For Misc</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AddMarkEntry"
                                                                   name="menuName[AddMarkEntry]"
                                                                   value="1" <?= in_array("AddMarkEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="AddMarkEntry" class="permisnCheck">Marks Entry For Additional</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SkillMarkEntry"
                                                                   name="menuName[SkillMarkEntry]"
                                                                   value="1" <?= in_array("SkillMarkEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="SkillMarkEntry" class="permisnCheck">Marks Entry For Skill</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TestMarkEntry"
                                                                   name="menuName[TestMarkEntry]"
                                                                   value="1" <?= in_array("TestMarkEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="TestMarkEntry" class="permisnCheck">Marks Entry From Test Marks</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ExamDateEntry"
                                                                   name="menuName[ExamDateEntry]"
                                                                   value="1" <?= in_array("ExamDateEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="ExamDateEntry" class="permisnCheck">Class Wise Exam Date Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ManualAttEntry"
                                                                   name="menuName[ManualAttEntry]"
                                                                   value="1" <?= in_array("ManualAttEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="ManualAttEntry" class="permisnCheck">Class Wise Manual Att. Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ClassSecUpdateAfterMEntry"
                                                                   name="menuName[ClassSecUpdateAfterMEntry]"
                                                                   value="1" <?= in_array("ClassSecUpdateAfterMEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationEntry"/>
                                                            <label for="ClassSecUpdateAfterMEntry" class="permisnCheck">Class Section Update After Mrks. Entry</label>
                                                        </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-3">Examination Test Marks</h4>
                                                            <input type="checkbox" id="examinationTestMarks"
                                                                   name="menuName[ExaminationTestMarks]"
                                                                   value="1" <?= in_array("ExaminationTestMarks=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('examinationTestMarks',this.id);"/>
                                                            <label for="examinationTestMarks"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngTestName"
                                                                   name="menuName[MngTestName]"
                                                                   value="1" <?= in_array("MngTestName=1", $menuData) ? 'checked' : '' ?>  class="examinationTestMarks"/>
                                                            <label for="MngTestName" class="permisnCheck">Manage Test Name</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TestMarksEntry"
                                                                   name="menuName[TestMarksEntry]"
                                                                   value="1" <?= in_array("TestMarksEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationTestMarks"/>
                                                            <label for="TestMarksEntry" class="permisnCheck">Test Marks Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TestReport"
                                                                   name="menuName[TestReport]"
                                                                   value="1" <?= in_array("TestReport=1", $menuData) ? 'checked' : '' ?>  class="examinationTestMarks"/>
                                                            <label for="TestReport" class="permisnCheck">Test Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TestSms"
                                                                   name="menuName[TestSms]"
                                                                   value="1" <?= in_array("TestSms=1", $menuData) ? 'checked' : '' ?>  class="examinationTestMarks"/>
                                                            <label for="TestSms" class="permisnCheck">Test Sms</label>
                                                        </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-3">Examination Competition</h4>
                                                            <input type="checkbox" id="examinationCompetition"
                                                                   name="menuName[examinationCompetition]"
                                                                   value="1" <?= in_array("examinationCompetition=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('examinationCompetition',this.id);"/>
                                                            <label for="examinationCompetition"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CompName"
                                                                   name="menuName[CompName]"
                                                                   value="1" <?= in_array("CompName=1", $menuData) ? 'checked' : '' ?>  class="examinationCompetition"/>
                                                            <label for="CompName" class="permisnCheck">Manage Competition Name</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CompMrksEntry"
                                                                   name="menuName[CompMrksEntry]"
                                                                   value="1" <?= in_array("CompMrksEntry=1", $menuData) ? 'checked' : '' ?>  class="examinationCompetition"/>
                                                            <label for="CompMrksEntry" class="permisnCheck">Competition Marks Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CompReport"
                                                                   name="menuName[CompReport]"
                                                                   value="1" <?= in_array("CompReport=1", $menuData) ? 'checked' : '' ?>  class="examinationCompetition"/>
                                                            <label for="CompReport" class="permisnCheck">Competition Report</label>
                                                        </div>


                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-3">Examination Report</h4>
                                                            <input type="checkbox" id="examinationReport"
                                                                   name="menuName[examinationReport]"
                                                                   value="1" <?= in_array("examinationReport=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('examinationReport',this.id);"/>
                                                            <label for="examinationReport"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="StudentWiseMarks"
                                                                   name="menuName[StudentWiseMarks]"
                                                                   value="1" <?= in_array("StudentWiseMarks=1", $menuData) ? 'checked' : '' ?>  class="examinationReport"/>
                                                            <label for="StudentWiseMarks" class="permisnCheck">Student Wise Marks</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ReportCard"
                                                                   name="menuName[ReportCard]"
                                                                   value="1" <?= in_array("ReportCard=1", $menuData) ? 'checked' : '' ?>  class="examinationReport"/>
                                                            <label for="ReportCard" class="permisnCheck">Report Card</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="GraphReport"
                                                                   name="menuName[GraphReport]"
                                                                   value="1" <?= in_array("GraphReport=1", $menuData) ? 'checked' : '' ?>  class="examinationReport"/>
                                                            <label for="GraphReport" class="permisnCheck">Graph Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="OriginalMarksReport"
                                                                   name="menuName[OriginalMarksReport]"
                                                                   value="1" <?= in_array("OriginalMarksReport=1", $menuData) ? 'checked' : '' ?>  class="examinationReport"/>
                                                            <label for="OriginalMarksReport" class="permisnCheck">Original Marks Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="OriginalAdditionalMarksReport"
                                                                   name="menuName[OriginalAdditionalMarksReport]"
                                                                   value="1" <?= in_array("OriginalAdditionalMarksReport=1", $menuData) ? 'checked' : '' ?>  class="examinationReport"/>
                                                            <label for="OriginalAdditionalMarksReport" class="permisnCheck">Original Additional Marks Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="CompiledSheet"
                                                                   name="menuName[CompiledSheet]"
                                                                   value="1" <?= in_array("CompiledSheet=1", $menuData) ? 'checked' : '' ?>  class="examinationReport"/>
                                                            <label for="CompiledSheet" class="permisnCheck">Compiled Sheet</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="InfoSheet"
                                                                   name="menuName[InfoSheet]"
                                                                   value="1" <?= in_array("InfoSheet=1", $menuData) ? 'checked' : '' ?>  class="examinationReport"/>
                                                            <label for="InfoSheet" class="permisnCheck">Info Sheet</label>
                                                        </div>



                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-3">Online Examination</h4>
                                                            <input type="checkbox" id="OnlineExamination"
                                                                   name="menuName[OnlineExamination]"
                                                                   value="1" <?= in_array("OnlineExamination=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('onlineExamination',this.id);"/>
                                                            <label for="OnlineExamination"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="SetQuesPaper"
                                                                   name="menuName[SetQuesPaper]"
                                                                   value="1" <?= in_array("SetQuesPaper=1", $menuData) ? 'checked' : '' ?>  class="onlineExamination"/>
                                                            <label for="SetQuesPaper" class="permisnCheck">Set Question Paper</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="PublishPaper"
                                                                   name="menuName[PublishPaper]"
                                                                   value="1" <?= in_array("PublishPaper=1", $menuData) ? 'checked' : '' ?>  class="onlineExamination"/>
                                                            <label for="PublishPaper" class="permisnCheck">Publish  Paper</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="AnswerSheet"
                                                                   name="menuName[AnswerSheet]"
                                                                   value="1" <?= in_array("AnswerSheet=1", $menuData) ? 'checked' : '' ?>  class="onlineExamination"/>
                                                            <label for="AnswerSheet" class="permisnCheck">Answer Sheet</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MarksExport"
                                                                   name="menuName[MarksExport]"
                                                                   value="1" <?= in_array("MarksExport=1", $menuData) ? 'checked' : '' ?>  class="onlineExamination"/>
                                                            <label for="MarksExport" class="permisnCheck">Marks Export</label>
                                                        </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Examination</h4>
                                                            <input type="checkbox" id="chkexm"
                                                                   name="menuName[Examination]"
                                                                   value="1" <?= in_array("Examination=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheckExam('examination');"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                            <input type="checkbox"
                                                                   id="selectExam"
                                                                   onChange="selectAllExam('examination');"/>
                                                            <label for="Add" class="permisnCheck">SELECT ALL</label>

                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term2Vnsp]" id="Term2Vnsp"
                                                                   value="1" <?= in_array("Term2Vnsp=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Term2Vnsp" class="permisnCheck">Term2-VNSP</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term2Vnsp2]" id="Term2Vnsp2"
                                                                   value="1" <?= in_array("Term2Vnsp2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Term2Vnsp2" class="permisnCheck">Term2-VNSP(2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term2VnspTopFive]" id="Term2VnspTopFive"
                                                                   value="1" <?= in_array("Term2VnspTopFive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Term2VnspTopFive" class="permisnCheck">Term2-VNSP(Top Five)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[FinalVnsp]" id="FinalVnsp"
                                                                   value="1" <?= in_array("FinalVnsp=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="FinalVnsp" class="permisnCheck">Final-VNSP</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[FinalVnspTopFive]" id="FinalVnspTopFive"
                                                                   value="1" <?= in_array("FinalVnspTopFive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="FinalVnspTopFive" class="permisnCheck">Final-VNSP(Top Five)</label>
                                                        </div>


                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard1]" id="reportCard1"
                                                                       value="1" <?= in_array("reportCard1=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard1" class="permisnCheck">1.Term-1-Aaryan Public</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard2]" id="reportCard2"
                                                                       value="1" <?= in_array("reportCard2=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label style="font-size:13px" for="reportCard2" class="permisnCheck">2.Term-1(With Instruction)-Aaryan Global</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox"
                                                                       name="menuName[reportCard3]" id="reportCard3"
                                                                       value="1"
                                                                       value="1" <?= in_array("reportCard3=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label style="font-size:13px" for="reportCard3" class="permisnCheck">3.Term-1(with co-scholostic)-Sacred Soul</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox"
                                                                       name="menuName[reportCard4]" id="reportCard4"
                                                                       value="1"
                                                                       value="1" <?= in_array("reportCard4=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label style="font-size:13px" for="reportCard4" class="permisnCheck">4.Term-1(Top Five)-Aadrsh</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard5]" id="reportCard5"
                                                                       value="1" <?= in_array("reportCard5=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard5" class="permisnCheck">5.Term-1(2)-Aaryan Public</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard6]" id="reportCard6"
                                                                       value="1" <?= in_array("reportCard6=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard6" class="permisnCheck">6.Term-1(3)-Sacred Soul</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard7]" id="reportCard7"
                                                                       value="1" <?= in_array("reportCard7=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard7" class="permisnCheck">7.Term-1(4)-Aadrsh</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard8]" id="reportCard8"
                                                                       value="1" <?= in_array("reportCard8=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label style="font-size:13px" for="reportCard8" class="permisnCheck">8.Term-1(5)-Aaryan Global</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard9]" id="reportCard9"
                                                                       value="1" <?= in_array("reportCard9=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label style="font-size:13px" for="reportCard9" class="permisnCheck">9.Term-1(6)-ASSS</label>
                                                            </div>


                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard21]" id="reportCard21"
                                                                       value="1" <?= in_array("reportCard21=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard21" class="permisnCheck">10.Final-Aaryan Public</label>
                                                            </div>


                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard24]" id="reportCard24"
                                                                       value="1" <?= in_array("reportCard24=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard24" class="permisnCheck">11.Term-2-Aaryan Public</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard25]" id="reportCard25"
                                                                       value="1" <?= in_array("reportCard25=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard25" class="permisnCheck">12.Overall-2</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard26]" id="reportCard26"
                                                                       value="1" <?= in_array("reportCard26=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard26" class="permisnCheck">13.Final-RMM</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard27]" id="reportCard26"
                                                                       value="1" <?= in_array("reportCard27=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard27" class="permisnCheck">14.Term2-RMM</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard28]" id="reportCard28"
                                                                       value="1" <?= in_array("reportCard28=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard28" class="permisnCheck">15.Term-2-Aadrsh</label>
                                                            </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard36]" id="reportCard36"
                                                                   value="1" <?= in_array("reportCard36=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="reportCard36" class="permisnCheck">16.Term-2(AGS-2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard37]" id="reportCard37"
                                                                   value="1" <?= in_array("reportCard37=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="reportCard37" class="permisnCheck">17.Term-2(AGS-3)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard38]" id="reportCard38"
                                                                   value="1" <?= in_array("reportCard38=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="reportCard38" class="permisnCheck">18.Overall-RMM</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term_overallVN]" id="term_overallVN"
                                                                   value="1" <?= in_array("term_overallVN=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="term_overallVN" class="permisnCheck">19.Overall-VN</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[reportCard39]" id="reportCard39"
                                                                   value="1"
                                                                   value="1" <?= in_array("reportCard39=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="reportCard3" class="permisnCheck">20.Term-2-ssbv</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[reportCard56]" id="reportCard56"
                                                                   value="1"
                                                                   value="1" <?= in_array("reportCard56=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="reportCard56" class="permisnCheck">20.Term-2(Ssbv-2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard40]" id="reportCard40"
                                                                   value="1" <?= in_array("reportCard40=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="reportCard40" class="permisnCheck">21.Final-PIS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard46]" id="reportCard46"
                                                                   value="1" <?= in_array("reportCard46=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="reportCard46" class="permisnCheck">22.Term-2-Sacred</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard10]" id="reportCard10"
                                                                   value="1" <?= in_array("reportCard10=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="reportCard10" class="permisnCheck">23.Term-1(2024-25)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard49]" id="reportCard49"
                                                                   value="1" <?= in_array("reportCard49=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label style="font-size:13px" for="reportCard49" class="permisnCheck">24.Term-1(2024-2025)Junior</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard50]" id="reportCard50"
                                                                   value="1" <?= in_array("reportCard50=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="reportCard50" class="permisnCheck">25.Term-1(2.1)-Aaryan Public</label>
                                                        </div>
                                                        <div class
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard51]" id="reportCard51"
                                                                   value="1" <?= in_array("reportCard51=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="reportCard51" class="permisnCheck">26.Term-1(VNSP)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard52]" id="reportCard52"
                                                                   value="1" <?= in_array("reportCard52=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="reportCard52" class="permisnCheck">27.Term1-VNSP(Top Five)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard53]" id="reportCard53"
                                                                   value="1" <?= in_array("reportCard53=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="reportCard53" class="permisnCheck">28.Term-1(VNSP)-2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard31]" id="reportCard31"
                                                                       value="1" <?= in_array("reportCard31=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard31" class="permisnCheck">Final-SWS</label>
                                                            </div>
                                                        <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard32]" id="reportCard32"
                                                                       value="1" <?= in_array("reportCard32=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard32" class="permisnCheck">Term-2-AGS</label>
                                                            </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard34]"
                                                                   value="1" <?= in_array("reportCard34=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall Adarsh</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard42]"
                                                                   value="1" <?= in_array("reportCard42=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall RPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard35]"
                                                                   value="1" <?= in_array("reportCard35=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Fianl-Adarsh</label>
                                                        </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[FinalTerm2gurukul]" id="FinalTerm2gurukul"
                                                                       value="1" <?= in_array("FinalTerm2gurukul=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="FinalTerm2gurukul" class="permisnCheck">Final Term2-Gurukul</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[finalGurukul]" id="finalGurukul"
                                                                       value="1" <?= in_array("finalGurukul=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="finalGurukul" class="permisnCheck">Final Gurukul</label>
                                                            </div>
                                                        <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard41]" id="reportCard41"
                                                                       value="1" <?= in_array("reportCard41=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard41" class="permisnCheck">Term-1(AGS)</label>
                                                            </div>
                                                        <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[finalAaryanGlobal]" id="finalAaryanGlobal"
                                                                       value="1" <?= in_array("finalAaryanGlobal=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="finalAaryanGlobal" class="permisnCheck">Final Aaryan Global</label>
                                                            </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[finalBBISUKG]" id="finalBBISUKG"
                                                                   value="1" <?= in_array("finalBBISUKG=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="finalBBISUKG" class="permisnCheck">Final BBIS UKG</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[finalAaryanGlobal2]" id="finalAaryanGlobal2"
                                                                       value="1" <?= in_array("finalAaryanGlobal2=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="finalAaryanGlobal2" class="permisnCheck">Final Aaryan Global-2</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[FinalTerm2vsss]" id="FinalTerm2vsss"
                                                                       value="1" <?= in_array("FinalTerm2vsss=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="FinalTerm2vsss" class="permisnCheck">Final Term2vsss</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard43]" id="reportCard43"
                                                                       value="1" <?= in_array("reportCard43=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard43" class="permisnCheck">Final Term2-VN</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[termOverallvsss]" id="termOverallvsss"
                                                                       value="1" <?= in_array("termOverallvsss=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="termOverallvsss" class="permisnCheck">Term-Overall(vsss)without header</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[termOverallvsss2]" id="termOverallvsss2"
                                                                       value="1" <?= in_array("termOverallvsss2=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="termOverallvsss2" class="permisnCheck">Term-Overall-2(vsss)without header</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[reportCard44]" id="reportCard44"
                                                                       value="1" <?= in_array("reportCard44=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="reportCard44" class="permisnCheck">Term-Overall(vsss)with header</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[Periodic]"
                                                                       value="1" <?= in_array("Periodic=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="Add" class="permisnCheck">Preodic 30 Term-1</label>
                                                            </div>
                                                        <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[Periodic1]"
                                                                       value="1" <?= in_array("Periodic1=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="Add" class="permisnCheck">Preodic 30 Term-1(1)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                                <input type="checkbox" name="menuName[Periodic2t1]"
                                                                       value="1" <?= in_array("Periodic2t1=1", $menuData) ? 'checked' : '' ?>
                                                                       class="examination"/>
                                                                <label for="Add" class="permisnCheck">Periodic2(Term-1)</label>
                                                        </div>


                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Periodic-BALAJI]"
                                                                   value="1" <?= in_array("Periodic-BALAJI=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-1)-BALAJI</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Pt2Balaji]"
                                                                   value="1" <?= in_array("Pt2Balaji=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">PT-2(Term-1)-BALAJI</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Pt3Balaji]"
                                                                   value="1" <?= in_array("Pt3Balaji=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">PT-3(Term-2)-BALAJI</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Pt3Balaji-blank]"
                                                                   value="1" <?= in_array("Pt3Balaji-blank=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">PT-3(Term-2)-BALAJI-BLANK</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termoverallBalaJi]"
                                                                   value="1" <?= in_array("termoverallBalaJi=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall Balaji</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termoverallBalaJi2]"
                                                                   value="1" <?= in_array("termoverallBalaJi2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall Balaji-2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term2BestFiveBalaJi]"
                                                                   value="1" <?= in_array("term2BestFiveBalaJi=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 Best Five Balaji</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[PT-1-X]"
                                                                   value="1" <?= in_array("PT-1-X=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">PT-1-X</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Periodic30Adnl]"
                                                                               value="1" <?= in_array("Periodic30Adnl=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-1)-Addnl</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Periodic30Term112]"
                                                                               value="1" <?= in_array("Periodic30Term112=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-1)1.2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Periodic30Term1121]"
                                                                               value="1" <?= in_array("Periodic30Term1121=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-1)1.2.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox" name="menuName[PeriodicCS]"
                                                                               value="1" <?= in_array("PeriodicCS=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-1)CS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox" name="menuName[Periodic2CS]"
                                                                               value="1" <?= in_array("Periodic2CS=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic2(Term-1)CS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox" name="menuName[Periodic3CS]"
                                                                               value="1" <?= in_array("Periodic3CS=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic3(Term-2)CS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Periodic3CS-2]"
                                                                   value="1" <?= in_array("Periodic3CS-2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic3(Term-2)-Efive</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[PeriodicCSJ]"
                                                                               value="1" <?= in_array("PeriodicCSJ=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-1)CSJ</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Periodic-Unit]"
                                                                               value="1" <?= in_array("Periodic-Unit=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic Unit</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[reportCard47]"
                                                                               value="1" <?= in_array("reportCard47=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic Term-2 Unit</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[PA1]"
                                                                               value="1" <?= in_array("PA1=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">PA-1</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[PT2]"
                                                                               value="1" <?= in_array("PT2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">PT2 Term-1</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[PERIODICTEST2]"
                                                                   value="1" <?= in_array("PERIODICTEST2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">PERIODIC TEST-2(Term-1)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[PeriodicTest4]"
                                                                   value="1" <?= in_array("PeriodicTest4=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">PERIODIC TEST-4(Term-2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[SA1TERM1]"
                                                                               value="1" <?= in_array("SA1TERM1=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">SA-1(Term-1)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[SA1TERM12]"
                                                                               value="1" <?= in_array("SA1TERM12=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">SA-1(Term-1)2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                               name="menuName[Periodic-2]"
                                                                               value="1" <?= in_array("Periodic-2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic(Term-1)-2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                               name="menuName[Term1-Ut12]"
                                                                               value="1" <?= in_array("Term1-Ut12=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(Unit 1&2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                               name="menuName[Periodic2-2122]"
                                                                               value="1" <?= in_array("Periodic2-2122=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-2)(2021-22)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                               name="menuName[periodiac2]"
                                                                               value="1" <?= in_array("periodiac2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-Test(Term-2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                               name="menuName[paterm1]"
                                                                               value="1" <?= in_array("paterm1=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">PA1-Term1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                               name="menuName[unitTerm2]"
                                                                               value="1" <?= in_array("unitTerm2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Unit(Term-2)-SRS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                               name="menuName[Periodic2-2122.2]"
                                                                               value="1" <?= in_array("Periodic2-2122.2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-2)(2021-22).2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                               name="menuName[Periodic2-2122.3]"
                                                                               value="1" <?= in_array("Periodic2-2122.3=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-2)(2021-22).3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Periodic2]"
                                                                   value="1" <?= in_array("Periodic2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Preodic 30 Term-2</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Periodic3]"
                                                                   value="1" <?= in_array("Periodic3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Preodic 30 Term-2 Junior</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                               name="menuName[Periodic2-2]"
                                                                               value="1" <?= in_array("Periodic2-2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(Term-2)2</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-1]"
                                                                               value="1" <?= in_array("Term-1=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (with co-scholastic)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_with_instruction]"
                                                                               value="1" <?= in_array("term1_with_instruction=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(With Instruction)</label>

                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termoverallsawan]"
                                                                   value="1" <?= in_array("termoverallsawan=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-overall(With Instruction)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termoverallsawan1.1]"
                                                                   value="1" <?= in_array("termoverallsawan1.1=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-overall1.1(With Instruction)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard45]"
                                                                   value="1" <?= in_array("reportCard45=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">overall-shiva</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard57]"
                                                                   value="1" <?= in_array("reportCard57=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 ( Shiva Gurukul )</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard58]"
                                                                   value="1" <?= in_array("reportCard58=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 ( SG 9TH )</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[overallsvs]"
                                                                   value="1" <?= in_array("overallsvs=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall SVS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[term1_with_instruction2]"
                                                                               value="1" <?= in_array("term1_with_instruction2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(With Instruction 5-Point)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term2_with_instruction2]"
                                                                   value="1" <?= in_array("term2_with_instruction2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2(With Instruction 5-Point)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final_with_instruction2]"
                                                                   value="1" <?= in_array("Final_with_instruction2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final(With Instruction 9-Point)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final_with_instruction2.1]"
                                                                   value="1" <?= in_array("Final_with_instruction2.1=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final2.1(With Instruction 9-Point)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_with_instruction3]"
                                                                   value="1" <?= in_array("term1_with_instruction3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1(With Instruction 9-Point)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard20]"
                                                                   value="1" <?= in_array("reportCard20=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term2-Final</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final_with_additional]"
                                                                   value="1" <?= in_array("Final_with_additional=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final With additional</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_with_instMhd]"
                                                                               value="1" <?= in_array("term1_with_instMhd=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(With Instruction MHD)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term2_with_instMhd]"
                                                                               value="1" <?= in_array("term2_with_instMhd=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2(With Instruction MHD)</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[TermWthCoScholosticMHD]"
                                                                               value="1" <?= in_array("TermWthCoScholosticMHD=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(With CoScholostic MHD)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[TermOverallWthCoScholosticMHD]"
                                                                               value="1" <?= in_array("TermOverallWthCoScholosticMHD=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall(With CoScholostic MHD)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[TermWthCoScholosticDSS]"
                                                                               value="1" <?= in_array("TermWthCoScholosticDSS=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(With CoScholostic DSS)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_BS]"
                                                                               value="1" <?= in_array("term1_BS=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1-BS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[finalterm1_BS2]"
                                                                   value="1" <?= in_array("finalterm1_BS2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck"> Final Term-1-BS2</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_BS2]"
                                                                   value="1" <?= in_array("term1_BS2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1-BS2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term2_BS2]"
                                                                   value="1" <?= in_array("term2_BS2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2-BS2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[final_BS2]"
                                                                   value="1" <?= in_array("final_BS2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final-BS2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term1_RPS]"
                                                                               value="1" <?= in_array("Term1_RPS=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 RPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_with_grade]"
                                                                               value="1" <?= in_array("term1_with_grade=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (With Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_with_grade_dd]"
                                                                               value="1" <?= in_array("term1_with_grade_dd=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1-DD (With Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[final_with_grade_dd]"
                                                                   value="1" <?= in_array("final_with_grade_dd=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final-DD (With Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[final_with_grade_dd2]"
                                                                   value="1" <?= in_array("final_with_grade_dd2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final-DD2 (With Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_with_grade_dd2]"
                                                                               value="1" <?= in_array("term1_with_grade_dd2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1-DD2 (With Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term2_with_grade_dd2_pre]"
                                                                   value="1" <?= in_array("term2_with_grade_dd2_pre=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2-DD2 (Pre)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Final2Cps]"
                                                                   value="1" <?= in_array("Final2Cps=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final 2 CPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[reportCard48]"
                                                                   value="1" <?= in_array("reportCard48=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (Gurukul)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term2_with_grade]"
                                                                               value="1" <?= in_array("term2_with_grade=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 (With Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1_with_grade2]"
                                                                               value="1" <?= in_array("term1_with_grade2=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(With Grade)2</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term-1-without-co-scholostic]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term-1-without-co-scholostic=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (without co-scholastic)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[TermWthoutCoScholosticOS]"
                                                                   value="1"
                                                                   value="1" <?= in_array("TermWthoutCoScholosticOS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Trm-1(withoutCo-scholostic/Hdr)OS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[TermWthCoScholosticHPS]"
                                                                   value="1"
                                                                   value="1" <?= in_array("TermWthCoScholosticHPS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(with co-scholostic)HPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[TermWthCoScholosticSphir]"
                                                                   value="1"
                                                                   value="1" <?= in_array("TermWthCoScholosticSphir=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(with co-scholostic)SFSD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[TermWthCoScholosticSSS]"
                                                                   value="1"
                                                                   value="1" <?= in_array("TermWthCoScholosticSSS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(with co-scholostic)SSS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[TermWthCoScholosticSSK]"
                                                                   value="1"
                                                                   value="1" <?= in_array("TermWthCoScholosticSSK=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(with co-scholostic)SSK</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term1_CoScholosticSVS]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term1_CoScholosticSVS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(Co-scholostic)SVS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term2_CoScholosticSVS]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term2_CoScholosticSVS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2(Co-scholostic)SVS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[TermWthCoScholosticJS]"
                                                                   value="1"
                                                                   value="1" <?= in_array("TermWthCoScholosticJS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(with co-scholostic)JS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term1TGS]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term1TGS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(TGS)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term-1-without-co-scholostic]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term-1-without-co-scholostic=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(without co-scholostic)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term1withoutcoscholostic1_1]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term1withoutcoscholostic1_1=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(without co-scholostic)1.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term1withoutcoscholostic1_2]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term1withoutcoscholostic1_2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(without co-scholostic)1.2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox"
                                                                   name="menuName[Term1_bestPT]"
                                                                   value="1"
                                                                   value="1" <?= in_array("Term1_bestPT=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(Best PT1-PT2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term-1-without-co-scholostic-junior]"
                                                                   value="1" <?= in_array("Term-1-without-co-scholostic-junior=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (without co-scholastic)(Junior)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox"
                                                                   name="menuName[Term-1-without-co-scholostic2]"
                                                                   value="1" <?= in_array("Term-1-without-co-scholostic2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (without co-scholastic)2</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term-1-without-co-scholostic3]"
                                                                   value="1" <?= in_array("Term-1-without-co-scholostic3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(without co-scholostic)-3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term1withoutcoscholostic3_1]"
                                                                   value="1" <?= in_array("Term1withoutcoscholostic3_1=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(without co-scholostic)-3.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term1withoutcoscholostic3_2]"
                                                                   value="1" <?= in_array("Term1withoutcoscholostic3_2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(without co-scholostic)-3.2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term_2_wot_co_sch_3]"
                                                                   value="1" <?= in_array("Term_2_wot_co_sch_3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2(without co-scholostic)-3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term1withoutcoscholostic3_3]"
                                                                   value="1" <?= in_array("Term1withoutcoscholostic3_3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1(without co-scholostic)-3.3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox"
                                                                   name="menuName[Term1_DS]"
                                                                   value="1" <?= in_array("Term1_DS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1-DS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox"
                                                                   name="menuName[term2_pa2]"
                                                                   value="1" <?= in_array("term2_pa2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2(PA2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                               name="menuName[Term-1-bestfive]"
                                                                               value="1" <?= in_array("Term-1-bestfive=1", $menuData) ? 'checked' : '' ?>
                                                                               class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 Best Five</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                   name="menuName[Term-1-bestfive2]"
                                                                   value="1" <?= in_array("Term-1-bestfive2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 Best Five(2023-24)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                   name="menuName[pt2bestfive2]"
                                                                   value="1" <?= in_array("pt2bestfive2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">PT2 Best Five(2023-24)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox"
                                                                   name="menuName[Term-1-bestfiveperiodic]"
                                                                   value="1" <?= in_array("Term-1-bestfiveperiodic=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 Best Five Periodic</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-1-assesment]"
                                                                   value="1" <?= in_array("Term-1-assesment=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (Assesment Marks)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox" name="menuName[Term1PSEB]"
                                                                   value="1" <?= in_array("Term1PSEB=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 PSEB</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                             <input type="checkbox" name="menuName[Term2PSEB]"
                                                                   value="1" <?= in_array("Term2PSEB=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 PSEB</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Term-1-j-assesment]"
                                                                   value="1" <?= in_array("Term-1-j-assesment=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-1 (Assesment Marks) Junior</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term1-withoutheader]"
                                                                   value="1" <?= in_array("Term1-withoutheader=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1 (Without Header)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term1_bestpt_wthoutheader]"
                                                                   value="1" <?= in_array("Term1_bestpt_wthoutheader=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1 Best PT(Without Header)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term1withoutheader2]"
                                                                   value="1" <?= in_array("Term1withoutheader2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1 (Without Header)-2</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term1_wthout_overall_total]"
                                                                   value="1" <?= in_array("Term1_wthout_overall_total=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1 (Without overall total)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[PeriodicPa2]"
                                                                   value="1" <?= in_array("PeriodicPa2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(PA2)CS</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[PeriodicPa3]"
                                                                   value="1" <?= in_array("PeriodicPa3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(PA3)CS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[PeriodicRPSPa2]"
                                                                   value="1" <?= in_array("PeriodicRPSPa2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(PA2)-RPS</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[PeriodicPa2Mhd]"
                                                                   value="1" <?= in_array("PeriodicPa2Mhd=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic-30(PA2)-MHD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-2]"
                                                                   value="1" <?= in_array("Term-2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 (with co-scholastic)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term2WthoutCoScholosticMS]"
                                                                   value="1" <?= in_array("Term2WthoutCoScholosticMS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2(without co-scholostic)MS</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term2Olive]"
                                                                   value="1" <?= in_array("Term2Olive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 (OS)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term2Olive2]"
                                                                   value="1" <?= in_array("Term2Olive2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 (OS2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[OverallOlive]"
                                                                   value="1" <?= in_array("OverallOlive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall Olive</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Finalpt3]"
                                                                   value="1" <?= in_array("Finalpt3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final PT-3</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-2-bestfive]"
                                                                   value="1" <?= in_array("Term-2-bestfive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 Best Five</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[PeriodicBestFive]"
                                                                   value="1" <?= in_array("PeriodicBestFive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Periodic Best Five-SS</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term-2-bestfiveperiodic]"
                                                                   value="1" <?= in_array("Term-2-bestfiveperiodic=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 Best Five Periodic</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-2-overall]"
                                                                   value="1" <?= in_array("Term-2-overall=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 Overall</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Term-2-overall2]"
                                                                   value="1" <?= in_array("Term-2-overall2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 Overall 2</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                         name="menuName[Term-2-overall3]"
                                                                         value="1" <?= in_array("Term-2-overall3=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 Overall 3</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                         name="menuName[Term-2-overall4]"
                                                                         value="1" <?= in_array("Term-2-overall4=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-2 Overall 4</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Term-overall]"
                                                                   value="1" <?= in_array("Term-overall=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[TermOverallSsbv]"
                                                                   value="1" <?= in_array("TermOverallSsbv=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall(Ssbv)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-overall-jnr]"
                                                                   value="1" <?= in_array("Term-overall-jnr=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall Junior</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[TermOverallJuniorSRS]"
                                                                   value="1" <?= in_array("TermOverallJuniorSRS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall Junior-SRS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[finalsw]"
                                                                   value="1" <?= in_array("finalsw=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final-SW</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[termOverallDN]"
                                                                   value="1" <?= in_array("termOverallDN=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall DN</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1SXS]"
                                                                   value="1" <?= in_array("term1SXS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1 SXS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term1SXS2]"
                                                                   value="1" <?= in_array("term1SXS2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term1 SXS-2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[termOverallSXS]"
                                                                   value="1" <?= in_array("termOverallSXS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall SXS</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Termoverall2]"
                                                                   value="1" <?= in_array("Termoverall2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall.2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Termoverall2_1]"
                                                                   value="1" <?= in_array("Termoverall2_1=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall.2.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Termoverall4]"
                                                                   value="1" <?= in_array("Termoverall4=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall.4</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Termoverall5]"
                                                                   value="1" <?= in_array("Termoverall5=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall.5</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Termoverall3]"
                                                                   value="1" <?= in_array("Termoverall3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-overall.3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Termoverall_Bestfive2]"
                                                                   value="1" <?= in_array("Termoverall_Bestfive2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-overall-Best five2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Termoverall_SS]"
                                                                   value="1" <?= in_array("Termoverall_SS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-overall-SS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Termoverall_BestfiveS3]"
                                                                   value="1" <?= in_array("Termoverall_BestfiveS3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-overall-Best fiveS3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termOverallDetailGrade]"
                                                                   value="1" <?= in_array("termOverallDetailGrade=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall(With Detail Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termOverallRPSW]"
                                                                   value="1" <?= in_array("termOverallRPSW=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall(WithGrade RW)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termOverallDetailGradewithoutheader]"
                                                                   value="1" <?= in_array("termOverallDetailGradewithoutheader=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck" style="font-size:x-small">Term-Overall(With Detail Grade) without header</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[TermoverallGradeS3]"
                                                                   value="1" <?= in_array("TermoverallGradeS3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall (With Grade)S3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-overall-grade]"
                                                                   value="1" <?= in_array("Term-overall-grade=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall (With Grade)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-overall-grade2]"
                                                                   value="1" <?= in_array("Term-overall-grade2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall (With Grade) 2</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-overall-grade3]"
                                                                   value="1" <?= in_array("Term-overall-grade3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall (With Grade) 3</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term-overall-withoutheader]"
                                                                   value="1" <?= in_array("Term-overall-withoutheader=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall (Without Header)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Termoverallwithoutheader2021]"
                                                                   value="1" <?= in_array("Termoverallwithoutheader2021=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck" style="font-size: x-small">Term-Overall (Without Header)(2020-21)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Term-overall-without-co-scholastic]"
                                                                   value="1" <?= in_array("Term-overall-without-co-scholastic=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall (Without Co-scholastic)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Termoveralljunior1]"
                                                                   value="1" <?= in_array("Termoveralljunior1=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall without header</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Termoveralljunior11]"
                                                                   value="1" <?= in_array("Termoveralljunior11=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall without header 1.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Termoveralljunior2]"
                                                                   value="1" <?= in_array("Termoveralljunior2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall1.2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Termoveralljunior3]"
                                                                   value="1" <?= in_array("Termoveralljunior3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall1.3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[term_overall-gurukul]"
                                                                   value="1" <?= in_array("term_overall-gurukul=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall (gurukul)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[term_overall_with_grade]"
                                                                   value="1" <?= in_array("term_overall_with_grade=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall (With Grade)1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[term_overall_with_gradeCPS]"
                                                                   value="1" <?= in_array("term_overall_with_gradeCPS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term-Overall (With Grade)CPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Final_with_gradeCPS]"
                                                                   value="1" <?= in_array("Final_with_gradeCPS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final with grade CPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[final_term1_mhd]"
                                                                   value="1" <?= in_array("final_term1_mhd=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final Term1-MHD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[final_term2_mhd]"
                                                                   value="1" <?= in_array("final_term2_mhd=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final Term2-MHD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[term2_mhd]"
                                                                   value="1" <?= in_array("term2_mhd=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term2-MHD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[final_term_overall]"
                                                                   value="1" <?= in_array("final_term_overall=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final Term Overall</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[final_term_overall11]"
                                                                   value="1" <?= in_array("final_term_overall11=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final Term Overall1.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[final_term_overall12]"
                                                                   value="1" <?= in_array("final_term_overall12=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final Term Overall1.2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[finalOverallDetailGradewithoutheader]"
                                                                   value="1" <?= in_array("finalOverallDetailGradewithoutheader=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck" style="font-size: x-small">Final(With Detail Grade) without header</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Final5Term1withgrade]"
                                                                   value="1" <?= in_array("Final5Term1withgrade=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final5-Term1 with grade</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Finalspst]"
                                                                   value="1" <?= in_array("Finalspst=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Finalspst</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[Final5Term2withgrade]"
                                                                   value="1" <?= in_array("Final5Term2withgrade=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final5-Term2 with grade</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox"
                                                                   name="menuName[FinalTerm2]"
                                                                   value="1" <?= in_array("FinalTerm2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final Term2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-overall-pseb]"
                                                                   value="1" <?= in_array("Term-overall-pseb=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall (PSEB)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-overall-hbse]"
                                                                   value="1" <?= in_array("Term-overall-hbse=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall (HBSE)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Term-overall2-hbse]"
                                                                   value="1" <?= in_array("Term-overall2-hbse=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Term Overall-2 (HBSE)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final9]"
                                                                   value="1" <?= in_array("Final9=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Without Header)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final]"
                                                                         value="1" <?= in_array("Final=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Term-1)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final2122]"
                                                                         value="1" <?= in_array("Final2122=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Term-1)(2021-2022)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Final21221]"
                                                                         value="1" <?= in_array("Final21221=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Term-1)(2021-2022)-1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[Final2ss]"
                                                                         value="1" <?= in_array("Final2ss=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Term-2)-SS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final2dd]"
                                                                   value="1" <?= in_array("Final2dd=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Term-2)-DD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[PreBoard]"
                                                                         value="1" <?= in_array("PreBoard=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">PreBoard-SS</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                           <input type="checkbox" name="menuName[FinalSCR]"
                                                                         value="1" <?= in_array("FinalSCR=1", $menuData) ? 'checked' : '' ?>
                                                                         class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final-SCR</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final-Term2]"
                                                                   value="1" <?= in_array("Final-Term2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Term-2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final-Term2-Bestfive]"
                                                                   value="1" <?= in_array("Final-Term2-Bestfive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final (Term-2)(Best Five)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final2]"
                                                                   value="1" <?= in_array("Final2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final3]"
                                                                   value="1" <?= in_array("Final3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final3 (With Max Marks)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final4]"
                                                                   value="1" <?= in_array("Final4=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final4 (With Percentage)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final5]"
                                                                   value="1" <?= in_array("Final5=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final 5 (With Co-scholastic)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final6]"
                                                                   value="1" <?= in_array("Final6=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final 6 (With Grade)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final6balaji2]"
                                                                   value="1" <?= in_array("Final6balaji2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final6 (with grade).2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final7]"
                                                                   value="1" <?= in_array("Final7=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final7</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final71]"
                                                                   value="1" <?= in_array("Final71=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final7.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final72]"
                                                                   value="1" <?= in_array("Final72=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final7.2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[overallBS]"
                                                                   value="1" <?= in_array("overallBS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall BS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[overallSbas]"
                                                                   value="1" <?= in_array("overallSbas=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall SBAS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final8]"
                                                                   value="1" <?= in_array("Final8=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final8</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[final81]"
                                                                   value="1" <?= in_array("final81=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final8.1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final8_wthout_overall_total]"
                                                                   value="1" <?= in_array("Final8_wthout_overall_total=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final8(without Overall Total)</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Final10]"
                                                                   value="1" <?= in_array("Final10=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Final8(Top Five Total)</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[OveralBBIS1]"
                                                                   value="1" <?= in_array("OveralBBIS1=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall(BBIS)-1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[OveralBBIS2]"
                                                                   value="1" <?= in_array("OveralBBIS2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall(BBIS)-2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[OveralBBIS3]"
                                                                   value="1" <?= in_array("OveralBBIS3=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Add" class="permisnCheck">Overall(BBIS)-3</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[finalBBIS]" id="finalBBIS"
                                                                   value="1" <?= in_array("finalBBIS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="finalBBIS" class="permisnCheck">Final-BBIS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[finalBBIS2]" id="finalBBIS2"
                                                                   value="1" <?= in_array("finalBBIS2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="finalBBIS2" class="permisnCheck">Final-BBIS(2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[termOverallDN2]" id="termOverallDN2"
                                                                   value="1" <?= in_array("termOverallDN2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="termOverallDN2" class="permisnCheck">Term-Overall-DN (2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[term_overall_with_grade_efive]" id="term_overall_with_grade_efive"
                                                                   value="1" <?= in_array("term_overall_with_grade_efive=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="term_overall_with_grade_efive" class="permisnCheck">Term-Overall (With Grade)Efive</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[Periodic2-2122]" id="Periodic2-2122"
                                                                   value="1" <?= in_array("Periodic2-2122=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="Periodic2-2122" class="permisnCheck">Periodic-30(Term-2)(2023-24)</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[TermOverall2_2]" id="TermOverall2_2"
                                                                   value="1" <?= in_array("TermOverall2_2=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="TermOverall2_2" class="permisnCheck">Term-Overall-2-New</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[TermOverall2_kdps]" id="TermOverall2_kdps"
                                                                   value="1" <?= in_array("TermOverall2_kdps=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="TermOverall2_kdps" class="permisnCheck">Term-Overall-2-KDPS</label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="checkbox" name="menuName[TermOverallPPS]" id="TermOverallPPS"
                                                                   value="1" <?= in_array("TermOverallPPS=1", $menuData) ? 'checked' : '' ?>
                                                                   class="examination"/>
                                                            <label for="TermOverallPPS" class="permisnCheck">Term-overall-PPS</label>
                                                        </div>
                                                     
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">Compile Sheet</h4>
                                                            <input type="checkbox" id="compilesheetall"
                                                                   name="menuName[Compilesheet]"
                                                                   value="1" <?= in_array("Compilesheet=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('compilesheet',this.id);"/>
                                                            <label for="compilesheetall"
                                                                   class="permisnCheck">ADD</label>

                                                            <input type="checkbox" id="selectCompliedSheet"
                                                                   onClick="checkUncheck('compilesheet',this.id);"/>
                                                            <label for="compilesheetall"
                                                                   class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BestFiveCS"
                                                                   name="menuName[BestFiveCS]"
                                                                   value="1" <?= in_array("BestFiveCS=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="BestFiveCS" class="permisnCheck">Periodic Best Five-SS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ActivityWiseOverall"
                                                                   name="menuName[ActivityWiseOverall]"
                                                                   value="1" <?= in_array("ActivityWiseOverall=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="ActivityWiseOverall" class="permisnCheck">OverAll(Activity Wise)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="ActivityWiseBestUTOverall"
                                                                   name="menuName[ActivityWiseBestUTOverall]"
                                                                   value="1" <?= in_array("ActivityWiseBestUTOverall=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="ActivityWiseBestUTOverall" class="permisnCheck">OverAll(Best Unit)(Activity Wise)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="term1"
                                                                   name="menuName[term1]"
                                                                   value="1" <?= in_array("term1=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="term1" class="permisnCheck">Term1</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="compiledSheet1"
                                                                   name="menuName[compiledSheet1]"
                                                                   value="1" <?= in_array("compiledSheet1=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="compiledSheet1" class="permisnCheck">Term1(1)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="compiledSheet2"
                                                                   name="menuName[compiledSheet2]"
                                                                   value="1" <?= in_array("compiledSheet2=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="compiledSheet2" class="permisnCheck">Term1(2)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="term1TopFive"
                                                                   name="menuName[term1TopFive]"
                                                                   value="1" <?= in_array("term1TopFive=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="term1TopFive" class="permisnCheck">Term-1(Top Five)SRS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="term1PBSNK"
                                                                   name="menuName[term1PBSNK]"
                                                                   value="1" <?= in_array("term1PBSNK=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="term1PBSNK" class="permisnCheck">Term-1(PB-SNK)SRS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="term2"
                                                                   name="menuName[term2]"
                                                                   value="1" <?= in_array("term2=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="term2" class="permisnCheck">Term2</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="term2SS"
                                                                   name="menuName[term2SS]"
                                                                   value="1" <?= in_array("term2SS=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="term2SS" class="permisnCheck">Term2-SS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="BestActivityWiseOverall"
                                                                   name="menuName[BestActivityWiseOverall]"
                                                                   value="1" <?= in_array("BestActivityWiseOverall=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="BestActivityWiseOverall" class="permisnCheck">OverAll-CPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="overallCps"
                                                                   name="menuName[overallCps]"
                                                                   value="1" <?= in_array("overallCps=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="overallCps" class="permisnCheck">OverAll-2-CPS</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="bestActivityWiseOverallGns"
                                                                   name="menuName[bestActivityWiseOverallGns]"
                                                                   value="1" <?= in_array("bestActivityWiseOverallGns=1", $menuData) ? 'checked' : '' ?>  class="compilesheet"/>
                                                            <label for="bestActivityWiseOverallGns" class="permisnCheck">OverAll-GNS</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-2">TimeTable</h4>
                                                            <input type="checkbox" id="timetableall"
                                                                   name="menuName[Timetable]"
                                                                   value="1" <?= in_array("Timetable=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheck('timetable',this.id);"/>
                                                            <label for="timetableall"
                                                                   class="permisnCheck">ADD</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngPeriod"
                                                                   name="menuName[MngPeriod]"
                                                                   value="1" <?= in_array("MngPeriod=1", $menuData) ? 'checked' : '' ?>  class="timetable"/>
                                                            <label for="MngPeriod" class="permisnCheck">Manage Period</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="MngTimeTable"
                                                                   name="menuName[MngTimeTable]"
                                                                   value="1" <?= in_array("MngTimeTable=1", $menuData) ? 'checked' : '' ?>  class="timetable"/>
                                                            <label for="MngTimeTable" class="permisnCheck">Manage Timetable</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="FreeStfList"
                                                                   name="menuName[FreeStfList]"
                                                                   value="1" <?= in_array("FreeStfList=1", $menuData) ? 'checked' : '' ?>  class="timetable"/>
                                                            <label for="FreeStfList" class="permisnCheck">Free Staff List</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">

                                                            <h4 class="col-md-2">Web Content</h4>
                                                            <input type="checkbox" id="chkwebcnt"
                                                                   name="menuName[Webcontent]"
                                                                   value="1" <?= in_array("Webcontent=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheckWeb('web');"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                            <input type="checkbox" id="selectWeb"
                                                                   onClick="selectAllWeb('web');"/>
                                                            <label for="Add" class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <b>Website Slider</b><br>
                                                            <input type="checkbox" name="menuName[WebsiteSlider]"
                                                                   value="1" <?= in_array("WebsiteSlider=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Contact Form</b><br>
                                                            <input type="checkbox" name="menuName[ContactForm]"
                                                                   value="1" <?= in_array("ContactForm=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Time Table</b><br>
                                                            <input type="checkbox" name="menuName[TimeTableSubMenu]"
                                                                   value="1" <?= in_array("TimeTableSubMenu=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Announcement</b><br>
                                                            <input type="checkbox" name="menuName[Announcement]"
                                                                   value="1" <?= in_array("Announcement=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Thought</b><br>
                                                            <input type="checkbox" name="menuName[Thought]"
                                                                   value="1" <?= in_array("Thought=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Live Class</b><br>
                                                            <input type="checkbox" name="menuName[LiveClass]"
                                                                   value="1" <?= in_array("LiveClass=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Testimonial</b><br>
                                                            <input type="checkbox" name="menuName[Testimonial]"
                                                                   value="1" <?= in_array("Testimonial=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>

                                                        <div class="col-md-3">
                                                            <b>Manage Complaint</b><br>
                                                            <input type="checkbox" name="menuName[Complaints]"
                                                                   value="1" <?= in_array("Complaints=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>

                                                        <div class="col-md-3">
                                                            <b>Photo Gallery&nbsp;</b><br>
                                                            <input type="checkbox" name="menuName[PhotoGallery]"
                                                                   value="1" <?= in_array("PhotoGallery=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Infrastructure Gallery</b><br>
                                                            <input type="checkbox"
                                                                   name="menuName[InfrastructureGallery]"
                                                                   value="1" <?= in_array("InfrastructureGallery=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Hub Gallery</b><br>
                                                            <input type="checkbox" name="menuName[HubGallery]"
                                                                   value="1" <?= in_array("HubGallery=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Media Gallery</b><br>
                                                            <input type="checkbox" name="menuName[MediaGallery]"
                                                                   value="1" <?= in_array("MediaGallery=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Tour Gallery</b><br>
                                                            <input type="checkbox" name="menuName[TourGallery]"
                                                                   value="1" <?= in_array("TourGallery=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Video&nbsp;Gallery</b><br>
                                                            <input type="checkbox" name="menuName[VideoGallery]"
                                                                   value="1" <?= in_array("VideoGallery=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Blog</b><br>
                                                            <input type="checkbox" name="menuName[Blog]" value="1"
                                                                   class="web" <?= in_array("Blog=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Achievement</b><br>
                                                            <input type="checkbox" name="menuName[Achievement]"
                                                                   value="1" <?= in_array("Achievement=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Topper</b><br>
                                                            <input type="checkbox" name="menuName[Topper]"
                                                                   value="1" <?= in_array("Topper=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Branch</b><br>
                                                            <input type="checkbox" name="menuName[Branch]" value="1"
                                                                   class="web" <?= in_array("Branch=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Social Plugin</b><br>
                                                            <input type="checkbox" name="menuName[SocialPlugin]"
                                                                   value="1" <?= in_array("SocialPlugin=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Visitor</b><br>
                                                            <input type="checkbox" name="menuName[Visitor]"
                                                                   value="1" <?= in_array("Visitor=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Appointment</b><br>
                                                            <input type="checkbox" name="menuName[Appointment]"
                                                                   value="1" <?= in_array("Appointment=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Reminder</b><br>
                                                            <input type="checkbox" name="menuName[Reminder]"
                                                                   value="1" <?= in_array("Reminder=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Display&nbsp;Photo Gallery</b><br>
                                                            <input type="checkbox" name="menuName[DisplayPhotoGallery]"
                                                                   value="1" <?= in_array("DisplayPhotoGallery=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Diet</b><br>
                                                            <input type="checkbox" name="menuName[Diet]" value="1"
                                                                   class="web" <?= in_array("Diet=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Food Menu</b><br>
                                                            <input type="checkbox" name="menuName[FoodMenu]" value="1"
                                                                   class="web" <?= in_array("FoodMenu=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Ptm Date</b><br>
                                                            <input type="checkbox" name="menuName[PtmDate]"
                                                                   value="1" <?= in_array("PtmDate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Popup</b><br>
                                                            <input type="checkbox" name="menuName[Popup]"
                                                                   value="1" <?= in_array("Popup=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Pdfs</b><br>
                                                            <input type="checkbox" name="menuName[Pdfs]"
                                                                   value="1" <?= in_array("Pdfs=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Direct PDfs</b><br>
                                                            <input type="checkbox" name="menuName[DirectPdfs]"
                                                                   value="1" <?= in_array("DirectPdfs=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Career Form</b><br>
                                                            <input type="checkbox" name="menuName[CareerForm]"
                                                                   value="1" <?= in_array("CareerForm=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Job Registration</b><br>
                                                            <input type="checkbox" name="menuName[JobRegistration]"
                                                                   value="1" <?= in_array("JobRegistration=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Job Application</b><br>
                                                            <input type="checkbox" name="menuName[JobApplication]"
                                                                   value="1" <?= in_array("JobApplication=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Online Enquiry</b><br>
                                                            <input type="checkbox" name="menuName[OnlineEnquiry]"
                                                                   value="1" <?= in_array("OnlineEnquiry=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Online Registration(New)</b><br>
                                                            <input type="checkbox" name="menuName[OnlineRegistration]"
                                                                   value="1" <?= in_array("OnlineRegistration=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>OldOnline Registration(Old)</b><br>
                                                            <input type="checkbox"
                                                                   name="menuName[OldOnlineRegistration]"
                                                                   value="1" <?= in_array("OldOnlineRegistration=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Online Survey</b><br>
                                                            <input type="checkbox"
                                                                   name="menuName[OnlineSurvey]"
                                                                   value="1" <?= in_array("OnlineSurvey=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>E Pass Form</b><br>
                                                            <input type="checkbox" name="menuName[EPassForm]"
                                                                   value="1" <?= in_array("EPassForm=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Consent Form</b><br>
                                                            <input type="checkbox" name="menuName[ConsentForm]"
                                                                   value="1"
                                                                   class="web" <?= in_array("ConsentForm=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Fee&nbsp;Enquiry List</b><br>
                                                            <input type="checkbox" name="menuName[FeeEnquiryList]"
                                                                   value="1" <?= in_array("FeeEnquiryList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Display Event</b><br>
                                                            <input type="checkbox" name="menuName[DisplayEvent]"
                                                                   value="1" <?= in_array("DisplayEvent=1", $menuData) ? 'checked' : '' ?>
                                                                   class="web"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Health Checkup</b><br>
                                                            <input type="checkbox" name="menuName[HealthCheckup]"
                                                                   value="1"
                                                                   class="web" <?= in_array("HealthCheckup=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Ptm Feedback</b><br>
                                                            <input type="checkbox" name="menuName[PtmFeedback]"
                                                                   value="1"
                                                                   class="web" <?= in_array("PtmFeedback=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Visitor Feedback</b><br>
                                                            <input type="checkbox" name="menuName[VisitorFeedback]"
                                                                   value="1"
                                                                   class="web" <?= in_array("VisitorFeedback=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Mandatory Disclosure</b><br>
                                                            <input type="checkbox" name="menuName[MandatoryDisclosure]"
                                                                   value="1"
                                                                   class="web" <?= in_array("MandatoryDisclosure=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class=" form-inline">
                                                            <h4 class="col-md-1">Setting</h4>
                                                            <input type="checkbox" id="chksting"
                                                                   name="menuName[Setting]"
                                                                   value="1" <?= in_array("Setting=1", $menuData) ? 'checked' : '' ?>
                                                                   onClick="checkUncheckSet('setting');"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                            
                                                            <input type="checkbox" id="selectSetting"
                                                                   onClick="checkUncheckSet('setting');"/>
                                                            <label for="Add" class="permisnCheck">SELECT ALL</label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <b>Cofiguration</b><br>
                                                            <input type="checkbox" name="menuName[Cofiguration]"
                                                                   value="1" <?= in_array("Cofiguration=1", $menuData) ? 'checked' : '' ?>
                                                                   class="setting"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Profile</b><br>
                                                            <input type="checkbox" name="menuName[Profile]"
                                                                   value="1" <?= in_array("Profile=1", $menuData) ? 'checked' : '' ?>
                                                                   class="setting"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Signature</b><br>
                                                            <input type="checkbox" name="menuName[Signature]"
                                                                   value="1" <?= in_array("Signature=1", $menuData) ? 'checked' : '' ?>
                                                                   class="setting"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Add User</b><br>
                                                            <input type="checkbox" name="menuName[AddUser]"
                                                                   value="1" <?= in_array("AddUser=1", $menuData) ? 'checked' : '' ?>
                                                                   class="setting"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Role&nbsp;Permission</b><br>
                                                            <input type="checkbox" name="menuName[RolePermission]"
                                                                   value="1" <?= in_array("RolePermission=1", $menuData) ? 'checked' : '' ?>
                                                                   class="setting"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Manage Session</b><br>
                                                            <input type="checkbox" name="menuName[ManageSession]"
                                                                   value="1"
                                                                   class="setting" <?= in_array("ManageSession=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>


                                                        <div class="col-md-3">
                                                            <b>Sms List</b><br>
                                                            <input type="checkbox" name="menuName[SmsList]"
                                                                   value="1" <?= in_array("SmsList=1", $menuData) ? 'checked' : '' ?>
                                                                   class="setting"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Sms&nbsp;Template</b><br>
                                                            <input type="checkbox" name="menuName[SmsTemplate]"
                                                                   value="1" <?= in_array("SmsTemplate=1", $menuData) ? 'checked' : '' ?>
                                                                   class="setting"/>
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Log List</b><br>
                                                            <input type="checkbox" name="menuName[LogList]"
                                                                   value="1"
                                                                   class="setting" <?= in_array("LogList=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Upload Homework List</b><br>
                                                            <input type="checkbox" name="menuName[UploadHomeworkList]"
                                                                   value="1"
                                                                   class="setting" <?= in_array("UploadHomeworkList=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <b>Live Class Link</b><br>
                                                            <input type="checkbox" name="menuName[LiveClassLink]"
                                                                   value="1"
                                                                   class="setting" <?= in_array("LiveClassLink=1", $menuData) ? 'checked' : '' ?> />
                                                            <label for="Add" class="permisnCheck">ADD</label>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="form-inline">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4>Library Student</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibStudent"
                                                                           name="menuName[LibStudent]"
                                                                           value="1" <?= in_array("LibStudent=1", $menuData) ? 'checked' : '' ?>
                                                                           onClick="checkUncheck('LibStudent',this.id);"  />
                                                                    <label for="LibStudent"
                                                                           class="permisnCheck">ADD</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="form-inline">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <h4>Master</h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="checkbox" id="LibStuMaster"
                                                                                   name="menuName[LibStuMaster]"
                                                                                   onClick="checkUncheck('LibStudentMaster',this.id);"
                                                                                   value="1" <?= in_array("LibStuMaster=1", $menuData) ? 'checked' : '' ?>  class="LibStudent"/>
                                                                            <label for="LibStuMaster" class="permisnCheck">Add</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">

                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngCls"
                                                                           name="menuName[LibMngCls]"
                                                                           value="1" <?= in_array("LibMngCls=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStudentMaster"/>
                                                                    <label for="LibMngCls" class="permisnCheck"> Manage Class</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngSec"
                                                                           name="menuName[LibMngSec]"
                                                                           value="1" <?= in_array("LibMngSec=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStudentMaster"/>
                                                                    <label for="LibMngSec" class="permisnCheck"> Manage Section</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngCat"
                                                                           name="menuName[LibMngCat]"
                                                                           value="1" <?= in_array("LibMngCat=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStudentMaster"/>
                                                                    <label for="LibMngCat" class="permisnCheck"> Manage Category</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngGender"
                                                                           name="menuName[LibMngGender]"
                                                                           value="1" <?= in_array("LibMngGender=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStudentMaster"/>
                                                                    <label for="LibMngGender" class="permisnCheck"> Manage Gender</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngSubMaster"
                                                                           name="menuName[LibMngSubMaster]"
                                                                           value="1" <?= in_array("LibMngSubMaster=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStudentMaster"/>
                                                                    <label for="LibMngSubMaster" class="permisnCheck"> Manage Subject</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="form-inline">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <h4>Entry</h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="checkbox" id="LibStuEntry"
                                                                                   name="menuName[LibStuEntry]"
                                                                                   onClick="checkUncheck('LibStuEntry',this.id);"
                                                                                   value="1" <?= in_array("LibStuEntry=1", $menuData) ? 'checked' : '' ?>  class="LibStudent"/>
                                                                            <label for="LibStuEntry" class="permisnCheck">Add</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">

                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibAdmFrm"
                                                                           name="menuName[LibAdmFrm]"
                                                                           value="1" <?= in_array("LibAdmFrm=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStuEntry"/>
                                                                    <label for="LibAdmFrm" class="permisnCheck"> Admission Form</label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="form-inline">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <h4>Report</h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="checkbox" id="LibStuReport"
                                                                                   name="menuName[LibStuReport]"
                                                                                   onClick="checkUncheck('LibStuReport',this.id);"
                                                                                   value="1" <?= in_array("LibStuReport=1", $menuData) ? 'checked' : '' ?>  class="LibStudent"/>
                                                                            <label for="LibStuReport" class="permisnCheck">Add</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibRprAdmLst"
                                                                           name="menuName[LibRprAdmLst]"
                                                                           value="1" <?= in_array("LibRprAdmLst=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStuReport"/>
                                                                    <label for="LibRprAdmLst" class="permisnCheck"> Admission List</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibAdmTypWisStuRpt"
                                                                           name="menuName[LibAdmTypWisStuRpt]"
                                                                           value="1" <?= in_array("LibAdmTypWisStuRpt=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStuReport"/>
                                                                    <label for="LibAdmTypWisStuRpt" class="permisnCheck"> Admission Type Wise Student Report</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibAgeWiseStuReport"
                                                                           name="menuName[LibAgeWiseStuReport]"
                                                                           value="1" <?= in_array("LibAgeWiseStuReport=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStuReport"/>
                                                                    <label for="LibAgeWiseStuReport" class="permisnCheck"> Age Wise Student Report</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibClsWiseStrengthRpt"
                                                                           name="menuName[LibClsWiseStrengthRpt]"
                                                                           value="1" <?= in_array("LibClsWiseStrengthRpt=1", $menuData) ? 'checked' : '' ?>  class="LibStudent LibStuReport"/>
                                                                    <label for="LibClsWiseStrengthRpt" class="permisnCheck"> Class Wise Strength Report</label>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="form-inline">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4>Library Staff</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibStaff"
                                                                           name="menuName[LibStaff]"
                                                                           value="1" <?= in_array("LibStaff=1", $menuData) ? 'checked' : '' ?>
                                                                           onClick="checkUncheck('LibStaff',this.id);"  />
                                                                    <label for="LibStaff"
                                                                           class="permisnCheck">ADD</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="form-inline">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <h4>Master</h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="checkbox" id="LibStaffMaster"
                                                                                   name="menuName[LibStaffMaster]"
                                                                                   onClick="checkUncheck('LibStaffMaster',this.id);"
                                                                                   value="1" <?= in_array("LibStaffMaster=1", $menuData) ? 'checked' : '' ?>  class="LibStaff"/>
                                                                            <label for="LibStaffMaster" class="permisnCheck">Add</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">

                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngStfGender"
                                                                           name="menuName[LibMngStfGender]"
                                                                           value="1" <?= in_array("LibMngStfGender=1", $menuData) ? 'checked' : '' ?>  class="LibStaff LibStaffMaster"/>
                                                                    <label for="LibMngStfGender" class="permisnCheck"> Manage Gender</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngStfTitle"
                                                                           name="menuName[LibMngStfTitle]"
                                                                           value="1" <?= in_array("LibMngStfTitle=1", $menuData) ? 'checked' : '' ?>  class="LibStaff LibStaffMaster"/>
                                                                    <label for="LibMngStfTitle" class="permisnCheck"> Manage Title</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngStfType"
                                                                           name="menuName[LibMngStfType]"
                                                                           value="1" <?= in_array("LibMngStfType=1", $menuData) ? 'checked' : '' ?>  class="LibStaff LibStaffMaster"/>
                                                                    <label for="LibMngStfType" class="permisnCheck"> Manage Type</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibMngStfDpt"
                                                                           name="menuName[LibMngStfDpt]"
                                                                           value="1" <?= in_array("LibMngStfDpt=1", $menuData) ? 'checked' : '' ?>  class="LibStaff LibStaffMaster"/>
                                                                    <label for="LibMngStfDpt" class="permisnCheck"> Manage Department</label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="form-inline">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <h4>Entry</h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="checkbox" id="LibStaffEntry"
                                                                                   name="menuName[LibStaffEntry]"
                                                                                   onClick="checkUncheck('LibStaffEntry',this.id);"
                                                                                   value="1" <?= in_array("LibStaffEntry=1", $menuData) ? 'checked' : '' ?>  class="LibStaff"/>
                                                                            <label for="LibStaffEntry" class="permisnCheck">Add</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">

                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibStffEntry"
                                                                           name="menuName[LibStffEntry]"
                                                                           value="1" <?= in_array("LibStffEntry=1", $menuData) ? 'checked' : '' ?>  class="LibStaff LibStaffEntry"/>
                                                                    <label for="LibStffEntry" class="permisnCheck"> Staff Entry</label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="form-inline">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <h4>Report</h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="checkbox" id="LibStaffReport"
                                                                                   name="menuName[LibStaffReport]"
                                                                                   onClick="checkUncheck('LibStaffReport',this.id);"
                                                                                   value="1" <?= in_array("LibStaffReport=1", $menuData) ? 'checked' : '' ?>  class="LibStaff"/>
                                                                            <label for="LibStaffReport" class="permisnCheck">Add</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibRprStffLst"
                                                                           name="menuName[LibRprStffLst]"
                                                                           value="1" <?= in_array("LibRprStffLst=1", $menuData) ? 'checked' : '' ?>  class="LibStaff LibStaffReport"/>
                                                                    <label for="LibRprStffLst" class="permisnCheck"> Staff List</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibRprClsWiseStffLst"
                                                                           name="menuName[LibRprClsWiseStffLst]"
                                                                           value="1" <?= in_array("LibRprClsWiseStffLst=1", $menuData) ? 'checked' : '' ?>  class="LibStaff LibStaffReport"/>
                                                                    <label for="LibRprClsWiseStffLst" class="permisnCheck"> Class Wise Staff List</label>
                                                                </div>


                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="form-inline">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4>Library Books Master</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibBookMaster"
                                                                           name="menuName[LibBookMaster]"
                                                                           value="1" <?= in_array("LibBookMaster=1", $menuData) ? 'checked' : '' ?>
                                                                           onClick="checkUncheck('LlbBookMaster',this.id);" />
                                                                    <label for="LibBookMaster"
                                                                           class="permisnCheck">ADD</label>

                                                                    <input type="checkbox" id="selectLiBookMaster"
                                                                           onClick="checkUncheck('LlbBookMaster',this.id);" />
                                                                    <label for="LibBookMaster"
                                                                           class="permisnCheck">SELECT ALL</label>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngSub"
                                                                   name="menuName[LibMngSub]"
                                                                   value="1" <?= in_array("LibMngSub=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngSub" class="permisnCheck">Manage Subject</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngPeriodicity"
                                                                   name="menuName[LibMngPeriodicity]"
                                                                   value="1" <?= in_array("LibMngPeriodicity=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngPeriodicity" class="permisnCheck">Manage Periodicity</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngTitle"
                                                                   name="menuName[LibMngTitle]"
                                                                   value="1" <?= in_array("LibMngTitle=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngTitle" class="permisnCheck">Manage Title</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngPubName"
                                                                   name="menuName[LibMngPubName]"
                                                                   value="1" <?= in_array("LibMngPubName=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngPubName" class="permisnCheck">Manage Publisher Name</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngPubPlc"
                                                                   name="menuName[LibMngPubPlc]"
                                                                   value="1" <?= in_array("LibMngPubPlc=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngPubPlc" class="permisnCheck">Manage Publisher Place</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngCurrency"
                                                                   name="menuName[LibMngCurrency]"
                                                                   value="1" <?= in_array("LibMngCurrency=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngCurrency" class="permisnCheck">Manage Currency</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngAccMat"
                                                                   name="menuName[LibMngAccMat]"
                                                                   value="1" <?= in_array("LibMngAccMat=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngAccMat" class="permisnCheck">Manage Accompanying Material</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngAuthor"
                                                                   name="menuName[LibMngAuthor]"
                                                                   value="1" <?= in_array("LibMngAuthor=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngAuthor" class="permisnCheck">Manage Author</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngBookCond"
                                                                   name="menuName[LibMngBookCond]"
                                                                   value="1" <?= in_array("LibMngBookCond=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngBookCond" class="permisnCheck">Manage Book Condition</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngBookStatus"
                                                                   name="menuName[LibMngBookStatus]"
                                                                   value="1" <?= in_array("LibMngBookStatus=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngBookStatus" class="permisnCheck">Manage Book Status</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngWithdrawal"
                                                                   name="menuName[LibMngWithdrawal]"
                                                                   value="1" <?= in_array("LibMngWithdrawal=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngWithdrawal" class="permisnCheck">Manage Withdrawal </label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngAccReg"
                                                                   name="menuName[LibMngAccReg]"
                                                                   value="1" <?= in_array("LibMngAccReg=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngAccReg" class="permisnCheck">Accession Register</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngJournal"
                                                                   name="menuName[LibMngJournal]"
                                                                   value="1" <?= in_array("LibMngJournal=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngJournal" class="permisnCheck">Manage Journal</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngJournalEntry"
                                                                   name="menuName[LibMngJournalEntry]"
                                                                   value="1" <?= in_array("LibMngJournalEntry=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngJournalEntry" class="permisnCheck">Journal Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngJournalReport"
                                                                   name="menuName[LibMngJournalReport]"
                                                                   value="1" <?= in_array("LibMngJournalReport=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngJournalReport" class="permisnCheck">Journal Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngMagazine"
                                                                   name="menuName[LibMngMagazine]"
                                                                   value="1" <?= in_array("LibMngMagazine=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngMagazine" class="permisnCheck">Manage Magazine</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngMagazineEntry"
                                                                   name="menuName[LibMngMagazineEntry]"
                                                                   value="1" <?= in_array("LibMngMagazineEntry=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngMagazineEntry" class="permisnCheck">Magazine Entry</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngMagazineReport"
                                                                   name="menuName[LibMngMagazineReport]"
                                                                   value="1" <?= in_array("LibMngMagazineReport=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngMagazineReport" class="permisnCheck">Magazine Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngNewsPaperList"
                                                                   name="menuName[LibMngNewsPaperList]"
                                                                   value="1" <?= in_array("LibMngNewsPaperList=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngNewsPaperList" class="permisnCheck">NewsPaper List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibMngNewsPaperEntry"
                                                                   name="menuName[LibMngNewsPaperEntry]"
                                                                   value="1" <?= in_array("LibMngNewsPaperEntry=1", $menuData) ? 'checked' : '' ?>  class="LlbBookMaster"/>
                                                            <label for="LibMngNewsPaperEntry" class="permisnCheck">Daily NewsPaper Entry</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="form-inline">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4>Library  ILL</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibILL"
                                                                           name="menuName[LibILL]"
                                                                           value="1" <?= in_array("LibILL=1", $menuData) ? 'checked' : '' ?>
                                                                           onClick="checkUncheck('LibILL',this.id);"  />
                                                                    <label for="LibILL"
                                                                           class="permisnCheck">ADD</label>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibIllCollege"
                                                                   name="menuName[LibIllCollege]"
                                                                   value="1" <?= in_array("LibIllCollege=1", $menuData) ? 'checked' : '' ?>  class="LibILL"/>
                                                            <label for="LibIllCollege" class="permisnCheck">College</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibIllDept"
                                                                   name="menuName[LibIllDept]"
                                                                   value="1" <?= in_array("LibIllDept=1", $menuData) ? 'checked' : '' ?>  class="LibILL"/>
                                                            <label for="LibIllDept" class="permisnCheck">Department</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibIllSub"
                                                                   name="menuName[LibIllSub]"
                                                                   value="1" <?= in_array("LibIllSub=1", $menuData) ? 'checked' : '' ?>  class="LibILL"/>
                                                            <label for="LibIllSub" class="permisnCheck">Subject</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibIllStaff"
                                                                   name="menuName[LibIllStaff]"
                                                                   value="1" <?= in_array("LibIllStaff=1", $menuData) ? 'checked' : '' ?>  class="LibILL"/>
                                                            <label for="LibIllStaff" class="permisnCheck">ILL</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="form-inline">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4>Library  OPAC</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibOpac"
                                                                           name="menuName[LibOpac]"
                                                                           value="1" <?= in_array("LibOpac=1", $menuData) ? 'checked' : '' ?>
                                                                           onClick="checkUncheck('LibOpac',this.id);"  />
                                                                    <label for="LibOpac"
                                                                           class="permisnCheck">ADD</label>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibOpacChkBook"
                                                                   name="menuName[LibOpacChkBook]"
                                                                   value="1" <?= in_array("LibOpacChkBook=1", $menuData) ? 'checked' : '' ?>  class="LibOpac"/>
                                                            <label for="LibOpacChkBook" class="permisnCheck">Check Book</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibOpacChkMagazine"
                                                                   name="menuName[LibOpacChkMagazine]"
                                                                   value="1" <?= in_array("LibOpacChkMagazine=1", $menuData) ? 'checked' : '' ?>  class="LibOpac"/>
                                                            <label for="LibOpacChkMagazine" class="permisnCheck">Check Magazine</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibOpacChkJournal"
                                                                   name="menuName[LibOpacChkJournal]"
                                                                   value="1" <?= in_array("LibOpacChkJournal=1", $menuData) ? 'checked' : '' ?>  class="LibOpac"/>
                                                            <label for="LibOpacChkJournal" class="permisnCheck">Check Journal</label>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="form-inline">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4>Library Issue/Return</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibIssueReturn"
                                                                           name="menuName[LibIssueReturn]"
                                                                           value="1" <?= in_array("LibIssueReturn=1", $menuData) ? 'checked' : '' ?>
                                                                           onClick="checkUncheck('libIssueReturn',this.id);"  />
                                                                    <label for="LibIssueReturn"
                                                                           class="permisnCheck">ADD</label>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibIssueToStu"
                                                                   name="menuName[LibIssueToStu]"
                                                                   value="1" <?= in_array("LibIssueToStu=1", $menuData) ? 'checked' : '' ?>  class="libIssueReturn"/>
                                                            <label for="LibIssueToStu" class="permisnCheck">Issue To Student</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibIssueToStff"
                                                                   name="menuName[LibIssueToStff]"
                                                                   value="1" <?= in_array("LibIssueToStff=1", $menuData) ? 'checked' : '' ?>  class="libIssueReturn"/>
                                                            <label for="LibIssueToStff" class="permisnCheck">Issue To Staff</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibIssueToILL"
                                                                   name="menuName[LibIssueToILL]"
                                                                   value="1" <?= in_array("LibIssueToILL=1", $menuData) ? 'checked' : '' ?>  class="libIssueReturn"/>
                                                            <label for="LibIssueToILL" class="permisnCheck">Issue To ILL</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibReservation"
                                                                   name="menuName[LibReservation]"
                                                                   value="1" <?= in_array("LibReservation=1", $menuData) ? 'checked' : '' ?>  class="libIssueReturn"/>
                                                            <label for="LibReservation" class="permisnCheck">Reservation</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibBookReturn"
                                                                   name="menuName[LibBookReturn]"
                                                                   value="1" <?= in_array("LibBookReturn=1", $menuData) ? 'checked' : '' ?>  class="libIssueReturn"/>
                                                            <label for="LibBookReturn" class="permisnCheck">Return</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="form-inline">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4>Library Report</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" id="LibReports"
                                                                           name="menuName[LibReports]"
                                                                           value="1" <?= in_array("LibReports=1", $menuData) ? 'checked' : '' ?>
                                                                           onClick="checkUncheck('libReports',this.id);"  />
                                                                    <label for="LibReports"
                                                                           class="permisnCheck">ADD</label>
                                                                    
                                                                    <input type="checkbox" id="selectLibReports"
                                                                            onClick="checkUncheck('libReports',this.id);"  />
                                                                    <label for="LibReports"
                                                                           class="permisnCheck">SELECT ALL</label>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibFineRpt"
                                                                   name="menuName[LibFineRpt]"
                                                                   value="1" <?= in_array("LibFineRpt=1", $menuData) ? 'checked' : '' ?>  class="libReports"/>
                                                            <label for="LibFineRpt" class="permisnCheck">Fine Report</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibStuLdr"
                                                                   name="menuName[LibStuLdr]"
                                                                   value="1" <?= in_array("LibStuLdr=1", $menuData) ? 'checked' : '' ?>  class="libReports"/>
                                                            <label for="LibStuLdr" class="permisnCheck">Student Ledger</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="TotStdBookIssue"
                                                                   name="menuName[TotStdBookIssue]"
                                                                   value="1" <?= in_array("TotStdBookIssue=1", $menuData) ? 'checked' : '' ?>  class="libReports"/>
                                                            <label for="TotStdBookIssue" class="permisnCheck">Total Book Issue List(Student Wise)</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibStffLdr"
                                                                   name="menuName[LibStffLdr]"
                                                                   value="1" <?= in_array("LibStffLdr=1", $menuData) ? 'checked' : '' ?>  class="libReports"/>
                                                            <label for="LibStffLdr" class="permisnCheck">Staff Ledger</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibBookIssueList"
                                                                   name="menuName[LibBookIssueList]"
                                                                   value="1" <?= in_array("LibBookIssueList=1", $menuData) ? 'checked' : '' ?>  class="libReports"/>
                                                            <label for="LibBookIssueList" class="permisnCheck">Book Issue List</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibBookIssueRegister"
                                                                   name="menuName[LibBookIssueRegister]"
                                                                   value="1" <?= in_array("LibBookIssueRegister=1", $menuData) ? 'checked' : '' ?>  class="libReports"/>
                                                            <label for="LibBookIssueRegister" class="permisnCheck">Book Issue Register</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" id="LibStockVerification"
                                                                   name="menuName[LibStockVerification]"
                                                                   value="1" <?= in_array("LibStockVerification=1", $menuData) ? 'checked' : '' ?>  class="libReports"/>
                                                            <label for="LibStockVerification" class="permisnCheck">Book Stock Verification</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <div class="form-group row gutter">


                                                <div class="form-group row gutter">
                                                    <?php
                                                    if ($section == 'edit') { ?>
                                                        <input type="hidden" name="editID" id="editID" class="boxBtn"
                                                               value="<?= $CommanObj->inscrape($CurrentMenuSetting['ID']); ?>">
                                                        <?php
                                                    } ?>
                                                    <input type="submit" name="submit" id="submit" class="boxBtn"
                                                           value="Submit" title="Submit" alt="Submit">
                                    </fieldset>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>


                <?php

                break;
            default :
                ?>
                <form name="frm" id="frm" action="?section=update" method="post">

                <?php include $baseROOT . "/include/validationMassage.php"; ?>
                <div class="panel-heading">
                    <h4><b>MANAGE MENU MASTER</b></h4>
                    <span style="float:right; margin-top:-20px;"> <a href="?section=add" title="+ Add Setting"><img
                                    src="<?= $baseURL; ?>/image/add.png" width="16" height="16" alt="Add"/> &nbsp;<b>ADD MENU SETTING</b></a></span>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover no-margin">
                            <thead>
                            <tr>
                                <th>SR. No</th>
                                <th>School Code</th>
                                <th>Session Start Date</th>
                                <th>Action&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $MenuSettingObj->getAllMenuSetting();

                            if ($MenuSettingObj->getNumRow() > 0) {
                                $count = 1;
                                while ($CurrentMenuSetting = $MenuSettingObj->getRow()) {
                                    ?>
                                    <tr>
                                        <td><b><?= $count ?></b><input type="hidden" name="ID_<?= $count; ?>"
                                                                       id="ID_<?= $count; ?>"
                                                                       value="<?= $CurrentMenuSetting['ID'] ?>"></td>
                                        <td><?= $CurrentMenuSetting['schoolCode'] ?></td>
                                        <td><?= $CurrentMenuSetting['sessionStartDate'] ?></td>

                                        <td><a href="?section=edit&ID=<?= $CurrentMenuSetting['ID'] ?>" class="Link"
                                               title="Edit"><img src="<?= $baseURL; ?>/image/edit.png" width="16"
                                                                 height="16" alt="Edit"/></a>&nbsp;<a
                                                    href="?section=delete&ID=<?= $CurrentMenuSetting['ID'] ?>"
                                                    class="colorRed Link"
                                                    onClick="return confirm('Are you sure you want to delete these Setting for this school.');"><img
                                                        src='<?= $baseURL; ?>/image/delete.png' border='0'
                                                        title='Delete' alt='Delete' width="16" height="16"/></a></td>
                                    </tr>

                                    <?php $count++;
                                }
                                echo "
				<tr><td colspan=9><input type=\"submit\" name=\"update\" id=\"update\" class=\"boxBtn\" value=\"Update\" title=\"Update\" alt=\"Update\" style=\"width:86px; margin:0px;\" /></tr></td>";
                            } else echo "<b>Sorry, No Menu Setting added yet.</b>";
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <input type="hidden" name="count" id="count" value="<?= $count; ?>">
                </form><?php
                break;
        }
        ?>
    </div>
    <!-- Main container ends -->

</div>
<!-- Dashboard wrapper ends -->


<!-- Container fluid ends -->

<!-- Footer start -->
<?php include $baseROOT . "/SuperAdmin/include/footer.php"; ?>
<!-- Footer end -->

</html>