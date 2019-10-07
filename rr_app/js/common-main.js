function redirect(url){ window.location.href = url; }
function openBlank(url){ window.open(url, '_blank'); }

function clearUserLog() {
	var del=confirm("Clear cannot be undone!\nAre you sure you want to clear log history?");
	if (del==true){
		$.ajax({
			url: 'user_log_clear.php',
			type: 'post',
			dataType: 'json',
			data: { user_id: encodeURIComponent(1)},
			beforeSend: function(x) {
                var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong> Please wait...</strong></div>';
				$('#notification-msg').html(triger_msg);
			},
			success: function(json) {
				if(json['resp']==1){
					var triger_msg = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> You have successfully cleared Log history!</div>';
	                    $('#notification-msg').html(triger_msg);
					setTimeout(function(){
						window.location.href = 'user_log.php';
					}, 2000);
				}else{
                    var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Failed while clearing Log history!</div>';
					$('#notification-msg').html(triger_msg);
				}
			}
		});	
	}
}

function clearErrorLog() {  
	var del=confirm("Clear cannot be undo!\nAre you sure you want to clear log history?");
	if (del==true){
	    $.ajax({
	        url: 'error_log_clear.php',
	        type: 'post',
	        dataType: 'json',
	        data: { user_id: encodeURIComponent(1) },
	        beforeSend: function (x) {
	            var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please Wait...</strong></div>';
	                    $('#notification-msg').html(triger_msg);
	        },
	        success: function (json) {
	            if (json['is_logged'] == 1) {
	                if (json['resp'] == 1) {
                        var triger_msg = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> You have successfully cleared Error history!</div>';
	                    $('#notification-msg').html(triger_msg);
	                    setTimeout(function () {
	                        window.location.href = 'error_log.php';
	                    }, 2000);
	                } else {
                        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Failed while clearing Error history!</div>';
	                    $('#notification-msg').html(triger_msg);
	                }
	            }else{
	                redirect('index.php');  //redirect for login when user logged out
	            }
	        }
	    });
	}
}


/*Start module*/
$(document).ready(function () {
    $("#searchModule").keyup(function () {
        var searchString = $("#searchModule").val();
        //var filter_status = $("#select_status").val();
        var filter_status = '';
        getModuleByStatus(searchString, filter_status, 1, 1);
    });
});

function getModuleByStatus(searchString,filter_status,mode,page) {
	if(mode==0){		
		location.href="module_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $("#searchModule").val();
	//var option_select='javascript:getModuleByStatus(\''+searchString+'\',this.value,1,1);';
	//$("#select_status").attr("onchange",option_select);
	$.ajax({
	    url: 'module_list_search.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	        $('html, body').animate({ scrollTop: 80 }, 'slow');
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
                 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}
/*End module*/

/*Start User Group*/

$(document).ready(function () {
    $("#searchUserGroup").keyup(function () {
        var searchString = $("#searchUserGroup").val();
        var filter_status = $("#select_status").val();
        getUserGroupByStatus(searchString, filter_status, 1, 1);
    });
});

function getUserGroupByStatus(searchString,filter_status,mode,page) {
    
	if(mode==0){		
		location.href="user_group.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $.trim($("#searchUserGroup").val());
	
	var option_select='javascript:getUserGroupByStatus(\''+searchString+'\',this.value,1,1);';
	
	$("#select_status").attr("onchange",option_select);	
	
	$.ajax({
		url: 'user_group_search.php',
		type: 'post',
		dataType: 'json',		
		data: { searchString: encodeURIComponent(searchString),filter_status: encodeURIComponent(filter_status),page: page },
		beforeSend: function(x) {
            var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
		},
		success: function(json) {			
		if (json['is_logged'] == 1) {			
			if(json['resp']==1){									
				$('#table_info').html(json['html']);
				$('#pagination_link').html(json['pagination']);
				$("#notification-msg").empty();
				$('html, body').animate({ scrollTop: 80 }, 'slow');				
			}else{
				$('#table_info').html(json['html']);
				$('#pagination_link').html(json['pagination']);
				$("#notification-msg").empty();
				$('html, body').animate({ scrollTop: 80 }, 'slow');
			}
            }else{
                 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
            }
		},
		error: function (xhr, ajaxOptions, thrownError){
			var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
		}
	});	
}
/*End User Group*/


/*Start USER*/

$(document).ready(function () {
    $("#searchUser").keyup(function () {
        var searchString = $("#searchUser").val();
        var filter_status = $("#select_status").val();
        getUserByStatus(searchString, filter_status, 1, 1);
        
    });
});

function getUserByStatus(searchString,filter_status,mode,page) {
    
	if(mode==0){		
		location.href="user_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $.trim($("#searchUser").val());
	
	var option_select='javascript:getUserByStatus(\''+searchString+'\',this.value,1,1);';
	
	$("#select_status").attr("onchange",option_select);

	$.ajax({
	    url: 'user_search.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
	            var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}
/*End USER*/


/*Start STUDENT*/

$(document).ready(function () {
    $("#searchStudent").keyup(function () {
        var searchString = $("#searchStudent").val();
        var filter_status = $("#select_status").val();
        getStudentByStatus(searchString, filter_status, 1, 1);        
    });
});

function getStudentByStatus(searchString,filter_status,mode,page) {
    
	if(mode==0){		
		location.href="student_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $.trim($("#searchStudent").val());
	
	var option_select='javascript:getStudentByStatus(\''+searchString+'\',this.value,1,1);';
	
	$("#select_status").attr("onchange",option_select);

	$.ajax({
	    url: 'student_search.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
	            var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}


/*****   Start Course   *****/
$(document).ready(function () {
    $("#searchCourse").keyup(function () {
        var searchString = $("#searchCourse").val();
        var filter_status = $("#select_status").val();
        getCourseByStatus(searchString, filter_status, 1, 1);
    });
});

function getCourseByStatus(searchString,filter_status,mode,page) {
    
	if(mode==0){		
		location.href="course_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $.trim($("#searchCourse").val());
	
	var option_select='javascript:getCourseByStatus(\''+searchString+'\',this.value,1,1);';
	
	$("#select_status").attr("onchange",option_select);

	$.ajax({
	    url: 'course_list_search.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
	            var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}
/*****   End Course     *****/


$(document).ready(function () {
	if($('#sameAddress').is(":checked")){
		$('#permanent_address').attr("readonly",true);
		$('input[name=p_town_village]').attr("readonly",true);
		$('input[name=p_state]').attr("readonly",true);
		$('input[name=p_city]').attr("readonly",true);
		$('input[name=p_pin]').attr("readonly",true);
	}

    $('#sameAddress').change(function () {
        if ($(this).is(":checked")) {
            $('#permanent_address').val($('#mailing_address').val());

            $('input[name=p_town_village]').val($('input[name=m_town_village]').val());
            $('input[name=p_state]').val($('input[name=m_state]').val());
            $('input[name=p_city]').val($('input[name=m_city]').val());
            $('input[name=p_pin]').val($('input[name=m_pin]').val());
			$('#permanent_address').attr("readonly",true);
			$('input[name=p_town_village]').attr("readonly",true);
			$('input[name=p_state]').attr("readonly",true);
			$('input[name=p_city]').attr("readonly",true);
			$('input[name=p_pin]').attr("readonly",true);

            return;
        }else{
			$('#permanent_address').attr("readonly",false);
			$('input[name=p_town_village]').attr("readonly",false);
			$('input[name=p_state]').attr("readonly",false);
			$('input[name=p_city]').attr("readonly",false);
			$('input[name=p_pin]').attr("readonly",false);
		}

        $('#permanent_address').val('');

        $('input[name=p_town_village]').val('');
        $('input[name=p_state]').val('');
        $('input[name=p_city]').val('');
        $('input[name=p_pin]').val('');
		

    });

    /*******************FEE***************************/

    //total payable fee//

    $("#concession, #late_fee").keyup(function () {
		// $('#payment').val(0);
		var concession = 0 ;
		var late_fee = 0 ;
        if ($(this).val().length === 0) {
            //$(this).val(0);
        }else{
			concession = parseInt($('#concession').val());
			 late_fee = parseInt($('#late_fee').val());
		}
        var total = parseInt($('#total').val());
        var payable_amount = (total - concession) + late_fee;
        var due = 0;
        var payment = parseInt($('#payment').val());
        if (payable_amount > 0) {
            $('#payable_amount').val(payable_amount);
            $('#payment').val(payable_amount);
            payment = $('#payment').val();
            $due = payable_amount - payment;
            $('#due').val(due);
            
        }
		
    });


    $("#payment").keyup(function () {
        var payment = 0;
		if ($(this).val().length === 0) {
            //$(this).val(0);
            $('#due').val(0);
        }else{
		payment = parseInt($('#payment').val());
	
		}

        var payable_amount = parseInt($('#payable_amount').val());
		if ($(this).val().length === 0) {
			$('#due').val(0);
		}else{
			var due = payable_amount - payment;
			if(due < 0){
				$('#due').val(0);
			}else{
				$('#due').val(due);
			}
		}
    });
    /*******************END FEE***************************/

});

/*End STUDENT*/

/*********************** START EMPLOYEE *********************/

$(document).ready(function () {
    $("#searchEmployee").keyup(function () {
		var searchString = $("#searchEmployee").val();
        var filter_status = $("#select_status").val();
        getEmployeeByStatus(searchString, filter_status, 1, 1);
        
    });
});

function getEmployeeByStatus(searchString,filter_status,mode,page) {
    
	if(mode==0){		
		location.href="employee_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $.trim($("#searchEmployee").val());
	
	var option_select='javascript:getEmployeeByStatus(\''+searchString+'\',this.value,1,1);';
	
	$("#select_status").attr("onchange",option_select);

	$.ajax({
	    url: 'employee_list_search.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
	            var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}

	/*********************** END EMPLOYEE *********************/




	/**********   Start Fee Breakup   **************/
$(document).ready(function () {
    $("#searchFeeBreakup").keyup(function () {
        var searchString = $("#searchFeeBreakup").val();
        var filter_status = '';
        getFeeBreakupByStatus(searchString, filter_status, 1, 1);
    });
});

function getFeeBreakupByStatus(searchString,filter_status,mode,page) {
	if(mode==0){		
		location.href="student_fee_breakup_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $("#searchFeeBreakup").val();
	
	$.ajax({
	    url: 'student_fee_breakup_list_search.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	        $('html, body').animate({ scrollTop: 80 }, 'slow');
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
                 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}
/*************  End Fee Breakup   ********************/

/*************  START COTA   ******************/
$(document).ready(function () {
    $("#searchCota").keyup(function () {
        var searchString = $("#searchCota").val();
        var filter_status = '';
        getCotaByStatus(searchString, filter_status, 1, 1);
    });
});

function getCotaByStatus(searchString,filter_status,mode,page) {
	if(mode==0){		
		location.href="student_cota_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $("#searchCota").val();
	
	$.ajax({
	    url: 'student_cota_list_search.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	        $('html, body').animate({ scrollTop: 80 }, 'slow');
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
                 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}
/*************  End COTA   ********************/


/*************  START RESET BUTTON   ********************/
$(document).ready(function () {
    $("#resetBtn").click(function () {
        $(".reset").val("");
        $(".reset_opt").val("0");
    });
});
/*************  End  RESET BUTTON   ********************/


/************* Start Employee Salary *********************/

$(document).ready(function () {
	$("#no_of_days").prop('disabled', true);
	$("#pay_salary").prop('disabled', true);
	$("#salary_to_date").blur(function(){
	flag="1";

		var to_date = $("#salary_to_date").val();
		var from_date = $("#salary_from_date").val();
		var error_from_date = isDate(from_date);
	var error_to_date = isDate(to_date);

	if(error_from_date === 'empty'){
		$('#error_from_date').html('Enter Date');
		flag = 0;
	}else if(error_from_date === false){
		$('#error_from_date').html('Invalid Date');
		flag = 0;
	}else{
		$('#error_from_date').html('');
	}
	
	if(error_to_date === 'empty'){
		$('#error_to_date').html('Enter Date');
		flag = 0;
	}else if(error_to_date === false){
		$('#error_to_date').html('Invalid Date');
		flag = 0;
	}else{
		$('#error_to_date').html('');
	}

	if(flag === 0){
		$('#table_fee_report').html('<tr><td colspan="8" class="font-red text-center">Enter Dates and click on Get Result</td</tr>');
		exit();
	}else{
		$('#error_to_date').html('');

	}

		$.ajax({
			url: 'date_diff_ajax.php',
			type: 'post',
			dataType: 'json',
			data: { to_date: encodeURIComponent(to_date), from_date: encodeURIComponent(from_date) },
			beforeSend: function (x) {
				var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
				$('#notification-msg').html(triger_msg);
				$('html, body').animate({ scrollTop: 80 }, 'slow');
			},
			success: function (json) {
				if (json['is_logged'] == 1) {
					if (json['resp'] == 1) {
						if(json['total_days'] <= 0){
							$('#total_days').html('0');
							$("#no_of_days").prop('disabled', true);
						}else{
							$('#total_days').html(json['total_days']);
							$("#no_of_days").prop('disabled', false);
							$("#no_of_days").val('0');
						}
						
						$('#number_of_days_of_month').val(json['number_of_days_of_month']);
						$('#error_from_date').html(json['error_from_date']);
						$('#error_to_date').html(json['error_to_date']);
						$("#notification-msg").empty();
					} else {
						/*$('#table_info').html(json['html']);
						$("#notification-msg").empty();
						$('html, body').animate({ scrollTop: 80 }, 'slow');*/
					}
				} else {
					 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
				$('#notification-msg').html(triger_msg);
					setTimeout(function () {
						redirect('index.php');//redirect for login when user logged out
					}, 5000);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
				$('#notification-msg').html(triger_msg);
			}
		});
	});
});

$(document).ready(function () {

	var salary_of_one_day;
	var basic = parseFloat($("#basic").html());
	var hra = parseFloat($('#hra').html());
	var special_allowance = parseFloat($('#special_allowance').html());
	var conveyance_allowance = parseFloat($('#conveyance_allowance').html());
	var mobile_allowance = parseFloat($('#mobile_allowance').html());
	var internet = parseFloat($('#internet').html());
	var education_allowance = parseFloat($('#education_allowance').html());	

	var total_earnings = basic + hra + special_allowance + conveyance_allowance + mobile_allowance + internet + education_allowance;
	$('#total_earnings').html(total_earnings);
	
	var pf = parseFloat($("#pf").html());
	var esi = parseFloat($("#esi").html());
	var professional_tax = parseFloat($("#professional_tax").html());
	var tds = parseFloat($("#tds").html());	

	pf = ((basic * pf) / 100);
	esi = ((basic * esi) / 100);
	professional_tax =  ((basic * professional_tax) / 100);
	tds = ((basic * tds) / 100);

	var total_deductions = pf + esi + professional_tax + tds;

	$('#total_deductions').html(total_deductions);

	var net_salary = total_earnings - total_deductions;

	$('#net_salary').html(net_salary);
	$('#total_salary').html(net_salary);
	
	salary_of_one_day = parseFloat(net_salary/30);

	(Math.round( salary_of_one_day * 100 )/100 ).toString(); //-> "1.1"
	salary_of_one_day = salary_of_one_day.toFixed(2);

	$('#salary_of_one_day').html(salary_of_one_day);
	
	$('#no_of_days').keyup(function () {

		var total_days = parseInt($('#total_days').html());
		var no_of_days = parseInt($('#no_of_days').val());		
		var number_of_days_of_month = $("#number_of_days_of_month").val();

		if(no_of_days > 0 && no_of_days <= total_days) {
			
			if(no_of_days != number_of_days_of_month){
				var no_of_days = $("#no_of_days").val();
				var calculated_salary = salary_of_one_day * no_of_days;

				(Math.round( calculated_salary * 100 )/100 ).toString(); //-> "1.1"
				calculated_salary = calculated_salary.toFixed(2);

				$('#calculated_salary').html(calculated_salary);
			}else{
				$('#calculated_salary').html(net_salary);
			}
		$("#pay_salary").prop('disabled', false);
		$('#error_no_of_days').html("");

		}else if(!(no_of_days > 0 && no_of_days <= total_days)){
			$("#pay_salary").prop('disabled', true);
			$('#calculated_salary').html(0);
			$('#error_no_of_days').html("Enter a valid no of days");
		}
	});	
});


function getEmployeePaidSalary(employee_id,page) {	
	
	$.ajax({
	    url: 'employee_paid_salary_ajax.php',
	    type: 'post',
	    dataType: 'json',
	    data: { employee_id: encodeURIComponent(employee_id), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	        //$('html, body').animate({ scrollTop: 80 }, 'slow');
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_paid_salary').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                //$('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                //$('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
                 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}

/************* End Employee Salary *********************/

/************* Student Fee Report *********************/
$(document).ready(function () {
	$("#download_report").prop('disabled', true);
});

function getStudentFeeReport(page) {
	$("#download_report").prop('disabled', true);
	from_date = $.trim($("#from_date").val());
	to_date = $.trim($("#to_date").val());
	
	var flag = 1;
	var error_from_date = isDate(from_date);
	var error_to_date = isDate(to_date);

	if(error_from_date === 'empty'){
		$('#error_from_date').html('Enter Date');
		flag = 0;
	}else if(error_from_date === false){
		$('#error_from_date').html('Invalid Date');
		flag = 0;
	}else{
		$('#error_from_date').html('');
	}
	
	if(error_to_date === 'empty'){
		$('#error_to_date').html('Enter Date');
		flag = 0;
	}else if(error_to_date === false){
		$('#error_to_date').html('Invalid Date');
		flag = 0;
	}else{
		$('#error_to_date').html('');
	}

	if(flag === 0){
		$("#download_report").prop('disabled', true);
		$('#table_fee_report').html('<tr><td colspan="8" class="font-red text-center">Enter Dates and click on Get Result</td</tr>');
		exit();
	}else{
		$('#error_to_date').html('');

	}
	
	$.ajax({
	    url: 'student_fee_report_ajax.php',
	    type: 'post',
	    dataType: 'json',
	    data: { from_date: from_date, to_date: to_date, page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);	        
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_fee_report').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
					$("#download_report").prop('disabled', false);
	            } else {
	                $('#table_fee_report').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();	                
	            }
	        } else {
                var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}

/**************** date validator ****************/
function isDate(txtDate)
{
    var currVal = txtDate;
    if(currVal == '')
        return 'empty';

    //var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
    var rxDatePattern = /^(\d{1,2})(-)(\d{1,2})(-)(\d{4})$/; //Declare Regex
    var dtArray = currVal.match(rxDatePattern); // is format OK?

    if (dtArray == null) 
        return false;

    //Checks for dd/mm/yyyy format.
    
    dtDay= dtArray[1];
	dtMonth = dtArray[3];
    dtYear = dtArray[5];        

    if (dtMonth < 1 || dtMonth > 12) 
        return false;
    else if (dtDay < 1 || dtDay> 31) 
        return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
        return false;
    else if (dtMonth == 2) 
    {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
    }
    return true;
}
/**************** End date validator ****************/





/***************** Start Subject *******************/
$(document).ready(function () {
    $("#search_subject").keyup(function () {
        var searchString = $("#search_subject").val();
        var filter_status = '';
        getSubjectByStatus(searchString, filter_status, 1, 1);
    });
});

function getSubjectByStatus(searchString,filter_status,mode,page) {
	if(mode==0){		
		location.href="subject_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $("#search_subject").val();
	
	$.ajax({
	    url: 'subject_list_ajax.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	        $('html, body').animate({ scrollTop: 80 }, 'slow');
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
                 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}

/***************** Start Exam *******************/
$(document).ready(function () {
    $("#searchExam").keyup(function () {
        var searchString = $("#searchExam").val();
        var filter_status = $("#select_status").val();
        getExamsByStatus(searchString, filter_status, 1, 1);
    });
});

function getExamsByStatus(searchString,filter_status,mode,page) {
    
	if(mode==0){		
		location.href="exam_list.php?filter_status="+filter_status;
		return 0;		
	}
	searchString = $.trim($("#searchExam").val());
	
	var option_select='javascript:getExamByStatus(\''+searchString+'\',this.value,1,1);';
	
	$("#select_status").attr("onchange",option_select);

	$.ajax({
	    url: 'exam_list_ajax.php',
	    type: 'post',
	    dataType: 'json',
	    data: { searchString: encodeURIComponent(searchString), filter_status: encodeURIComponent(filter_status), page: page },
	    beforeSend: function (x) {
	        var triger_msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please wait...</strong></div>';
	        $('#notification-msg').html(triger_msg);
	    },
	    success: function (json) {
	        if (json['is_logged'] == 1) {
	            if (json['resp'] == 1) {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            } else {
	                $('#table_info').html(json['html']);
	                $('#pagination_link').html(json['pagination']);
	                $("#notification-msg").empty();
	                $('html, body').animate({ scrollTop: 80 }, 'slow');
	            }
	        } else {
	            var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
	        }
	    },
	    error: function (xhr, ajaxOptions, thrownError) {
	        var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
	    }
	});	
}
/*****   End Exam   *****/


/*Start SET TOTAL MARKS*/

$(document).ready(function () {

    $("#exam_list_select").change(function () {
        var exam_code = $("#exam_list_select").val();
		if(exam_code != '0')			
			getCourseIdByExam(exam_code);
    });
});

function getCourseIdByExam(exam_code) {
	$.ajax({
		url: 'get_courseid_by_exam_ajax.php',
		type: 'post',
		dataType: 'json',
		data: { exam_code: exam_code },
		success: function(json) {
		if (json['is_logged'] == 1) {
			if(json['resp']==1){
				$('#course_list').html(json['html']);
				$('#marks_entry_form_by_subject').html('<tr><td class="font-red text-center">Please Select Exam, Class and subject</td></tr>');
				$('#subject_list_select').html('<option value="0">--- Please Select ---</option>');
				$('#set').hide();
				$('.error').html('');
			}else{
				$('#course_list').html('<option value="0">No Record</option>');
				$('#subject_list_select').html('<option value="0">No Record</option>');
				$('#marks_entry_form_by_subject').html('<tr><td class="font-red text-center">Please Select Exam, Class and subject</td></tr>');
				$('.error').html('');
				$('#set').hide();
			}
            }else{
                 var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
	        $('#notification-msg').html(triger_msg);
                setTimeout(function () {
	                redirect('index.php');//redirect for login when user logged out
	            }, 5000);
            }
		},
		error: function (xhr, ajaxOptions, thrownError){
			var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
		}
	});	
}

/*End SET TOTAL MARKS*/

/* Start Student Marks Entry */
$(document).ready(function () {
    $(".course_list_select").change(function () {
        var course_code = $("#course_list").val();
		if(course_code != '0')			
			getSubjectByCourseCode(course_code);
    });
});

function getSubjectByCourseCode(course_code) {
	$.ajax({
			url: 'student_marks_entry_ajax.php',
			type: 'post',
			dataType: 'json',
			data: { course_code: course_code },
			success: function(json) {
				if (json['is_logged'] == 1) {
					if(json['resp']== 1){
						$('#subject_list_select').html(json['html']);
						$('#set').hide();
						$('.error').html('');
						$('#marks_entry_form_by_subject').html('<tr><td class="font-red text-center">Please Select Subject!</td></tr>');
					}else{
						$('#subject_list_select').html('<option value="0">No Record</option>');
						$('#marks_entry_form_by_subject').html('<tr><td class="font-red text-center">No Record</td></tr>');
						$('#set').hide();
						$('.error').html('');
					}
				}else{
					var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
					$('#notification-msg').html(triger_msg);
					setTimeout(function () {
						redirect('index.php');//redirect for login when user logged out
					}, 5000);
				}
			},
			error: function (xhr, ajaxOptions, thrownError){
				var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
				$('#notification-msg').html(triger_msg);
			}
		});	
	}

$(document).ready(function () {
    $("#subject_list_select").change(function () {
        var course_code = $(".course_list_select").val();
        var subject_code = $("#subject_list_select").val();
        var exam_code = $("#exam_list_select").val();
		if(course_code > '0' && subject_code > '0' && exam_code > '0')			
			getMarksEntryForm(subject_code, course_code, exam_code);
    });
});

function getMarksEntryForm(subject_code, course_code, exam_code) {
	$.ajax({
		url: 'student_marks_entry_ajax1.php',
		type: 'post',
		dataType: 'json',
		data: { course_code: course_code, subject_code:subject_code, exam_code:exam_code},
		success: function(json) {
		if (json['is_logged'] == 1) {

			if(json['resp']== 1){

				$('#marks_entry_form_by_subject').html(json['html']);
				$('.error').html('');
				$('#notification-msg').empty();
				$('#set').show();
				
			}else{
				$('#marks_entry_form_by_subject').html('<tr><td class="font-red text-center">No Record</td></tr>');				
				$('#set').hide();
				$('#notification-msg').empty();
				$('.error').html('');
			}
		}else{
			var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>You are not authorised to access this page.</div>';
			$('#notification-msg').html(triger_msg);
				setTimeout(function () {
					redirect('index.php');//redirect for login when user logged out
				}, 5000);
			}
		},
		error: function (xhr, ajaxOptions, thrownError){
			var triger_msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! </strong>' + thrownError + '</div>';
	        $('#notification-msg').html(triger_msg);
		}
	});
}

/* End Student Marks Entry */