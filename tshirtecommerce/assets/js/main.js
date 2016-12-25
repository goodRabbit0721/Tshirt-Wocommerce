function login(e, t) {
    if (typeof t == "undefined") t = "";
    var n = true;
    if (e == "logged") {
        i = "";
        action = baseURL + "index.php/users/login"
    } else if (e == "facebook") {
        i = {
            fb: "facebook"
        };
        action = baseURL + "index.php/users/login"
    } else if (e == "register") {
        if (t != "") var r = "#fr-" + t + "-register";
        else var r = "#fr-register";
        n = jQuery(r).validate({
            event: "click"
        });
        var i = {};
        jQuery(r).find("input").each(function() {
            i[jQuery(this).attr("name")] = jQuery(this).val()
        });
        action = baseURL + "index.php/users/register"
    } else if (e == "forgot") {
        n = jQuery("#fr-forgot").validate({
            event: "click"
        });
        var s = jQuery("#forgot-email").val();
        var o = jQuery("#forgot-password").val();
        var u = jQuery("#forgot-cfpassword").val();
        i = {
            email: "" + s + "",
            password: "" + o + "",
            cf_password: "" + u + ""
        };
        action = baseURL + "index.php/users/forgot"
    } else if (e == "change_pass") {
        n = jQuery("#fr-change-pass").validate({
            event: "click"
        });
        var a = jQuery("#change-password").val();
        var f = jQuery("#change-cfpassword").val();
        i = {
            password: "" + a + "",
            cf_password: "" + f + ""
        };
        action = baseURL + "index.php/users/change_pass"
    } else {
        var r = jQuery(e).parents("form");
        n = jQuery("#" + r.attr("id")).validate({
            event: "click"
        });
        var i = {};
        r.find("input").each(function() {
            i[jQuery(this).attr("name")] = jQuery(this).val()
        });
        action = baseURL + "index.php/users/login"
    }
    if (n == true) {
        var l = jQuery(e);
        if (e == "register") l = jQuery("#register-button" + t);
        else if (e == "forgot") l = jQuery("#forgot-button");
        else if (e == "change_pass") l = jQuery("#change-button");
        l.button("loading");
        jQuery.ajax({
            type: "POST",
            url: action,
            data: i,
            dataType: "html",
            success: function(n) {
                if (n != "") {
                    if (typeof t == "undefined") {
                        jQuery(".close").click()
                    } else if (t == "cart") {
                        jQuery("#user-form-cart").remove()
                    }
                    if (n != 1) {
                        jQuery("#f-login").children(".modal-dialog").children(".modal-content").html(n);
                        var r = "";
                        r += "<li>";
                        r += '<a data-target="#f-login" data-toggle="modal" href="javascript:void(0)">' + myAccount + "</a>";
                        r += "</li>";
                        r += "<li>";
                        r += '<a href="' + baseURL + 'index.php/users/logout">' + logOut + "</a>";
                        r += "</li>";
                        jQuery(".menu-top").children("ul").html(r);
                        l.button("reset");
                        jQuery(".menu-top").children("ul").show();
                        user_id = document.getElementById("user-id").value
						// update module login.
						if(typeof login_url_post !== "undefined")
						{
							jQuery.ajax({
								type: "POST",
								url: login_url_post,
								data: "",
								dataType: "html",
								success: function(update) {
									jQuery('.module-login').html(update);
								}
							});
						}
                    } else {
                        alert(passSuccess)
                    }
                    l.button("reset")
                } else {
                    if (e == "register") {
                        alert(registerError)
                    } else if (e == "forgot" || e == "change_pass") {
                        alert(passError)
                    } else if (e != "logged") {
                        alert(loginError)
                    }
                    l.button("reset")
                }
            }
        })
    }
}
jQuery(function() {
    jQuery(".arrow-mobile").click(function() {
        var e = jQuery(this).attr("data");
        var t = jQuery("#dg-" + e).css("display");
        if (e == "right") {
            if (t == "none") {                
                jQuery("#dg-right").show();
                jQuery(this).addClass('active').css({
                    left: "-28px",
                    right: "auto"
                });
				jQuery(this).parent().css({
					'bottom': 'auto',
					'top': '60px'
				});
                jQuery(".accordion").accordion("refresh")
            } else {              
                jQuery("#dg-right").hide();
                jQuery(this).removeClass('active').css({
                    left: "auto",
                    right: "4px"
                });
				jQuery(this).parent().css({
					'bottom': '60px',
					'top': 'auto'
				});
            }
        }
        if (e == "left") {
            if (t == "none") {
                jQuery(this).children("i").attr("class", "glyphicons chevron-left");
                jQuery("#dg-left").show();
                jQuery(this).css({
                    left: "auto",
                    right: "-32px"
                });
                jQuery(".accordion").accordion("refresh")
            } else {
                jQuery(this).children("i").attr("class", "glyphicons chevron-right");
                jQuery("#dg-left").hide();
                jQuery(this).css({
                    left: "0",
                    right: "auto"
                })
            }
        }
    })
});
window.onresize = function() {
    jQuery(".accordion").accordion("refresh")
};
jQuery("document").ready(function() {
    jQuery("#tools-help").bind("click", function() {
        jQuery("#help-tabs").tabs({
            beforeLoad: function(e, t) {
                t.jqXHR.error(function() {
                    t.panel.html("Couldn't load this tab. We'll try to fix this as soon as possible. " + "If this wouldn't be a demo.")
                })
            }
        })
    });
    jQuery("#register-button").click(function() {
        login("register")
    });
    jQuery("#forgot-button").click(function() {
        login("forgot")
    });
    jQuery("#click_forgot").click(function() {
        jQuery("#f-login-content").hide();
        jQuery("#f-forgot-content").show()
    });
    jQuery("#click_login").click(function() {
        jQuery("#f-login-content").show();
        jQuery("#f-forgot-content").hide()
    })
})