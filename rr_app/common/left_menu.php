
  		<div class="col-md-2 sidebar">
  			<div class="row">
	<!-- uncomment code for absolute positioning tweek see top comment in css -->
	<div class="absolute-wrapper"> </div>
    
	<!-- Menu -->
	<div class="side-menu">
		<nav class="navbar navbar-default" role="navigation">
			<!-- Main Menu -->
			<div class="side-menu-container">
				<ul class="nav navbar-nav">
					<!--<li class="active"><a href="success.php"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-plane"></span> Active Link</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-cloud"></span> Link</a></li>-->

					<!-- Dropdown Students-->  <!--Student -->
                    <?php if($sqli_query->getPermission('STUD-MGMT')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl1" class="<?php if(isset($menu_student)) echo $menu_student; ?>">
							<span class="glyphicon glyphicon-user"></span> Student <span class="caret"></span>
						</a>

						<!-- Dropdown level 1 -->      
						<div id="dropdown-lvl1" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav" >
                                    <li class="<?php if(isset($menu_student_sub1)) echo $menu_student_sub1; ?>"><a href="student_list.php"> Student List </a></li>

									<li class="<?php if(isset($menu_student_sub6)) echo $menu_student_sub6; ?>"><a href="student_find.php"> Find Student </a></li>

									<li class="<?php if(isset($menu_student_sub2)) echo $menu_student_sub2; ?>"><a href="result_generate.php"> Result Generate </a></li>
								</ul>
								
							</div>
						</div>
					</li>   <!--School menu End-->
                    <?php } ?>

					<!-- Dropdown-->	 <!--Student Attendance -->
                    <?php if($sqli_query->getPermission('STUD-ATND')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl5" class="<?php if(isset($menu_student_attendance)) echo $menu_student_attendance; ?>">
							<span class="glyphicon glyphicon-hand-up"></span> Student Attendace <span class="caret"></span>
						</a>

						<!-- Dropdown level 1 -->     
						<div id="dropdown-lvl5" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav" >
									<li class="<?php if(isset($menu_student_attendance_sub1)) echo $menu_student_attendance_sub1; ?>"><a href="student_attendance.php"> Attendance </a></li>
								</ul>								
							</div>
						</div>
					</li>   <!--Student Attendance End-->
                    <?php } ?>
					
					<!-- Dropdown-->	 <!--Student FEE SUBMIT -->
					<!-- <?php if($sqli_query->getPermission('STUD-FEE-SBMT')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl8" class="<?php if(isset($menu_student)) echo $menu_student; ?>">
							<span class="glyphicon glyphicon-user"></span> Fee Submit <span class="caret"></span>
						</a> -->

						<!-- Dropdown level  -->      
						<!-- <div id="dropdown-lvl8" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav" >
                                    <li class="<?php if(isset($menu_student_sub1)) echo $menu_student_sub1; ?>"><a href="student_list.php"> Student List </a></li>

									<li class="<?php if(isset($menu_student_sub6)) echo $menu_student_sub6; ?>"><a href="student_find.php"> Find Student </a></li>
								</ul>
								
							</div>
						</div> -->
					<!-- </li> -->   <!-- END FEE SUBMIT-->
                    <?php } ?>

					<!-- Dropdown EXAM-->
                    <?php if($sqli_query->getPermission('EXAM')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl6" class="<?php if(isset($menu_exam)) echo $menu_exam; ?>">
							<span class="glyphicon glyphicon-tasks"></span> Exams <span class="caret"></span>
						</a>

						<!-- Dropdown level 1 -->      <!--Student -->
						<div id="dropdown-lvl6" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav" >
									<li class="<?php if(isset($menu_exam_sub1)) echo $menu_exam_sub1; ?>"><a href="exam_list.php"> Exam Type </a></li>

									<li class="<?php if(isset($menu_exam_sub2)) echo $menu_exam_sub2; ?>"><a href="set_exam_with_course.php"> Set Course to Exam</a></li>

									<li class="<?php if(isset($menu_exam_sub3)) echo $menu_exam_sub3; ?>"><a href="set_total_marks.php"> Set Total Marks</a></li>

									<li class="<?php if(isset($menu_exam_sub4)) echo $menu_exam_sub4; ?>"><a href="student_marks_entry.php"> Marks Entry </a></li>
								</ul>
								
							</div>
						</div>
					</li>   <!--END EXAM-->
                    <?php } ?>

					<!-- Dropdown STUDENT SETTING -->
                    <?php if($sqli_query->getPermission('STUD-SETS')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl7" class="<?php if(isset($menu_student_setting)) echo $menu_student_setting; ?>">
							<span class="glyphicon glyphicon-cog"></span> Setting <span class="caret"></span>
						</a>
						<!-- Dropdown level 1 -->      <!--Student -->
						<div id="dropdown-lvl7" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav">
                                    <li class="<?php if(isset($menu_student_setting_sub1)) echo $menu_student_setting_sub1; ?>"><a href="course_list.php"> Class List </a></li>

									<li class="<?php if(isset($menu_student_setting_sub2)) echo $menu_student_setting_sub2; ?>"><a href="fee_structure_set.php"> Fee Sturcture </a></li>

									<li class="<?php if(isset($menu_student_setting_sub3)) echo $menu_student_setting_sub3; ?>"><a href="student_fee_breakup_list.php"> Fee Breakup </a></li>									

									<li class="<?php if(isset($menu_student_setting_sub4)) echo $menu_student_setting_sub4; ?>"><a href="subject_list.php"> Subject </a></li>

									<li class="<?php if(isset($menu_student_setting_sub5)) echo $menu_student_setting_sub5; ?>"><a href="student_cota_list.php"> Cota </a></li>
								</ul>
								
							</div>
						</div>
					</li>   <!--End STUDENT SETTING -->
                    <?php } ?>

                    <!-- Dropdown level 3 -->      <!--Student Report-->
					<?php if($sqli_query->getPermission('STUD-RPRT')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl4" class="<?php if(isset($menu_student_report)) echo $menu_student_report; ?>">
							<span class="glyphicon glyphicon-stats "></span> Student Report <span class="caret"></span>
						</a>						
						<div id="dropdown-lvl4" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav">
                                    <li class="<?php if(isset($menu_student_fee_report_sub1)) echo $menu_student_fee_report_sub1; ?>"><a href="student_fee_report.php"> Fee Report </a></li>
								</ul>
							</div>
						</div>
					</li>      <!--Student Report End-->
                    <?php  } ?>

                    <!-- Dropdown level 3 -->      <!--Employee Management-->
					<?php if($sqli_query->getPermission('EMPL-MGMT')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl3" class="<?php if(isset($menu_employee)) echo $menu_employee; ?>">
							<span class="glyphicon glyphicon-user "></span> Employee <span class="caret"></span>
						</a>						
						<div id="dropdown-lvl3" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav">
                                    <li class="<?php if(isset($menu_employee_sub1)) echo $menu_employee_sub1; ?>"><a href="employee_list.php"> Employee List </a></li>
                                    <li class="<?php if(isset($menu_employee_sub2)) echo $menu_employee_sub2; ?>"><a href="employee_find.php">Find Employee</a></li>
									<li class="<?php if(isset($menu_employee_sub3)) echo $menu_employee_sub3; ?>"><a href="employee_pay_salary.php">Pay Salary</a></li>
								</ul>
							</div>
						</div>
					</li>      <!--Employee Management End-->
                    <?php  } ?>

                    <!-- Dropdown level 1 -->      <!--Team Management-->
					<?php if($sqli_query->getPermission('TEAM-MGMT')){ ?>
                    <li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#dropdown-lvl2" class="<?php if(isset($menu_team_mgmt)) echo $menu_team_mgmt; ?>">
							<span class="glyphicon glyphicon-user "></span> Team Management <span class="caret"></span>
						</a>

						<div id="dropdown-lvl2" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav">
                                    <li class="<?php if(isset($menu_team_mgmt_sub1)) echo $menu_team_mgmt_sub1; ?>"><a href="user_list.php"> User List </a></li>
                                    <li class="<?php if(isset($menu_team_mgmt_sub2)) echo $menu_team_mgmt_sub2; ?>"><a href="user_group.php"> User Group </a></li>
									<li class="<?php if(isset($menu_team_mgmt_sub3)) echo $menu_team_mgmt_sub3; ?>"><a href="module_list.php"> Module List </a></li>
                                    <li class="<?php if(isset($menu_team_mgmt_sub4)) echo $menu_team_mgmt_sub4; ?>"><a href="user_log.php"> Login Logs </a></li>
									<li class="<?php if(isset($menu_team_mgmt_sub5)) echo $menu_team_mgmt_sub5; ?>"><a href="error_log.php"> Error Logs </a></li>
								</ul>
							</div>
						</div>
					</li>      <!--Team Management End-->
                    <?php } ?>

					<!--<li><a href="#"><span class="glyphicon glyphicon-signal"></span> Link</a></li>-->

				</ul>
			</div><!-- /.navbar-collapse -->
		</nav>

	</div>
</div>  		
</div>
  		