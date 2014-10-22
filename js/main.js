$(function(){
  $("input:button,input:submit,input:reset").addClass("boton");
	$("input:text, input:password, textarea, select").addClass("combo");
	$('img').ToolTip({className:'tooltip',position:'mouse',delay: 200});
  $("#top_logo").click(function(){window.location='./'});
  if($.browser.msie==false){$("#top_menu a").hover(function(){$(this).animate({opacity: 1,height:"30px",marginTop:"-5px",color:"#ffffff",lineHeight:"30px"},150);},function(){$(this).animate({opacity:0.8,height:"25px",marginTop:"0px",color:"#ecf2f8",lineHeight:"25px"},100);});}
  $("#searchval1").data('oval',$("#searchval1").val());
  $("#searchval7").data('oval',$("#searchval7").val());
  $("#searchval8").data('oval',$("#searchval8").val());
  $("#searchval1, #searchval7, #searchval8").focus(function(){$(this).val("");$(this).addClass("focus");});
  $("#searchval1").blur(function(){if($(this).val()=='') $(this).val($(this).data('oval'));$(this).removeClass("focus");});
  $("#searchval7").blur(function(){if($(this).val()=='') $(this).val($(this).data('oval'));$(this).removeClass("focus");});
  $("#searchval8").blur(function(){if($(this).val()=='') $(this).val($(this).data('oval'));$(this).removeClass("focus");});
  $("#searchByDomain").click(function(){$("form#formds").trigger("submit");});
  $("#searchByUser").click(function(){$("form#formus").trigger("submit");});
  $("#searchval1").keypress(function(){if (e.which==13){$("form#formus").trigger("submit");}});
  $("#searchval8").keypress(function(){if (e.which==13){$("form#formus").trigger("submit");}});
  $("a,input:button,input:submit,input:reset").click(function(){$(this).blur()});
  $("a,input:button,input:submit,input:reset").focus(function(){$(this).blur()});
  $("table.list").each(function(){$(this).find("tr:first td:contains('Advanced Search')").parent().remove();});
  $(".checkAll").click(function(){$("table.list").find("input:checkbox").attr("checked",$(this).attr("checked")); });
  $("table.list").each(function(){$(this).attr("cellspacing","0");$(this).find("tr:not(:first) td.listtitle,tr:eq(1) td.listtitle").removeClass("listtitle").addClass("footer");});
  $("table.list tr td.list,table.list tr td.list2").parent().addClass("trList");
  $("table.list tr td.list,table.list tr td.list2").parent().hover(function(){ $(this).addClass("hover"); },function(){ $(this).removeClass("hover"); });
  $("#changeLang").change(function(){var lang = $(this).val();if(lang!=''){$.ajax({url:'HTM_AJAX_CHANGE_LANG',data:'lang='+lang,type:'GET',dataType:"json",success: function(data){if(data.error==0){location.reload();}else{alert("Error while changin language");}}});}});
  $(".randpass").click(function(){$("input[name='passwd'],input[name='passwd2'],.visiblepass").val(randPass());$(".pster .box").removeClass("s0 s1 s2 s4 s5").addClass("s3");$(".pster").find("span").text("Medium");});
  $("body").find("input[name='passwd2']").wrap('<div class="pster"></div>');
  $("body").find(".pster").append('<div class="box s0"><div>Strength: <span></span></div><div class="bar"><div></div></div></div>');
  $("input[name='passwd']").bind("keyup change",function(){$(".visiblepass").val("");var d=new Array();d[0]="Very Weak";d[1]="Very Weak";d[2]="Weak";d[3]="Medium";d[4]="Good";d[5]="Strong";var s=passwordStrength($(this).val());$(".pster .box").removeClass("s0 s1 s2 s3 s4 s5").addClass("s"+s);$(".pster").find("span").text(d[s]);});
  $("input.domss").bind("keyup change",function(){$(this).val(toLowerCase($(this).val()));});
  
});
function TB_load(){$("#pageloading:hidden").slideDown("fast");}
function TB_unload(){$("#pageloading:visible").slideUp("fast");}
function getPage(page, destination){$.ajax({type: "get", url: page, dataType: 'html', success: function(html){$("#"+destination).html(html);}});}
function passwordStrength(password){
var score=0;if(password.length>6){score++;}if((password.match(/[a-z]/) ) && ( password.match(/[A-Z]/))){score++;}
if(password.match(/\d+/)){score++;}if(password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)){score++;}if(password.length>12){score++;}return score;
}
function randPass(){
  charsl="abcdefghijklmnopqrstuvwxyz";charsu="ABCDEFGHIJKLMNOPQRSTUVWXYZ";nums="1234567890";simb="!.-_@#$+/";
  pass="";
  i=Math.floor(Math.random()*26); pass += charsl.charAt(i);
  i=Math.floor(Math.random()*26); pass += charsu.charAt(i);
  i=Math.floor(Math.random()*26); pass += charsu.charAt(i);
  i=Math.floor(Math.random()*10); pass += nums.charAt(i);
  i=Math.floor(Math.random()*10); pass += nums.charAt(i);
  i=Math.floor(Math.random()*9); pass += simb.charAt(i);
  i=Math.floor(Math.random()*26); pass += charsl.charAt(i);
  i=Math.floor(Math.random()*10); pass += nums.charAt(i);
  return pass;
}
