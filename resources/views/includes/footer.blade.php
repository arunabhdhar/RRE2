<!--contact start-->

<div id="contact">
    <div class="line7">
        <div class="container">
            <div class="row footer">
                <div class="col-md-12">
                    <h3>Subscribe for Our Newsletter!</h3>
                    <p>Subscribe to our newsletter email to get notification about fresh news, latest Spromos, giveaways and free stuff from Skew. Stay always up-to-date!</p>
                    <div class="fr">
                        <div style="display: inline-block;">
                            <input class="col-md-6 fEmail" name='Email' placeholder='Enter Your Email'/>
                            <a href="#" class="subS">Subscribe!</a>
                        </div>
                    </div>
                </div>
                <div class="soc col-md-12">
                    <ul>
                        <li class="soc1"><a href="#"></a></li>
                        <li class="soc2"><a href="#"></a></li>
                        <li class="soc3"><a href="#"></a></li>
                        <li class="soc4"><a href="#"></a></li>
                        <li class="soc6"><a href="#"></a></li>
                        <li class="soc7"><a href="#"></a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="lineBlack">
        <div class="container">
            <div class="row downLine">
                <div class="col-md-6 text-left copy">
                    <p>Copyright &copy; 2016 Demo site. </p>
                </div>
                <div class="col-md-6 text-right dm">
                    <ul id="downMenu">
                        <li class="active"><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li class="last"><a href="#contact">Contact</a></li>
                        <!--li><a href="#features">Features</a></li-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>		


<script src="{{ asset('js/jquery.prettyPhoto.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('jsbootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.slicknav.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".bhide").click(function() {
            $(".hideObj").slideDown();
            $(this).hide(); //.attr()
            return false;
        });
        $(".bhide2").click(function() {
            $(".container.hideObj2").slideDown();
            $(this).hide(); // .attr()
            return false;
        });

        $('.heart').mouseover(function() {
            $(this).find('i').removeClass('fa-heart-o').addClass('fa-heart');
        }).mouseout(function() {
            $(this).find('i').removeClass('fa-heart').addClass('fa-heart-o');
        });

        function sdf_FTS(_number, _decimal, _separator)
        {
            var decimal = (typeof (_decimal) != 'undefined') ? _decimal : 2;
            var separator = (typeof (_separator) != 'undefined') ? _separator : '';
            var r = parseFloat(_number)
            var exp10 = Math.pow(10, decimal);
            r = Math.round(r * exp10) / exp10;
            rr = Number(r).toFixed(decimal).toString().split('.');
            b = rr[0].replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g, "\$1" + separator);
            r = (rr[1] ? b + '.' + rr[1] : b);

            return r;
        }

        setTimeout(function() {
            $('#counter').text('0');
            $('#counter1').text('0');
            $('#counter2').text('0');
            setInterval(function() {

                var curval = parseInt($('#counter').text());
                var curval1 = parseInt($('#counter1').text().replace(' ', ''));
                var curval2 = parseInt($('#counter2').text());
                if (curval <= 707) {
                    $('#counter').text(curval + 1);
                }
                if (curval1 <= 12280) {
                    $('#counter1').text(sdf_FTS((curval1 + 20), 0, ' '));
                }
                if (curval2 <= 245) {
                    $('#counter2').text(curval2 + 1);
                }
            }, 2);

        }, 500);
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#menu').slicknav();

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        var $menu = $("#menuF");

        $(window).scroll(function() {
            if ($(this).scrollTop() > 100 && $menu.hasClass("default")) {
                $menu.fadeOut('fast', function() {
                    $(this).removeClass("default")
                            .addClass("fixed transbg")
                            .fadeIn('fast');
                });
            } else if ($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast', function() {
                    $(this).removeClass("fixed transbg")
                            .addClass("default")
                            .fadeIn('fast');
                });
            }
        });
    });
    //jQuery
</script>
<script>
    /*menu*/
    function calculateScroll() {
        var contentTop = [];
        var contentBottom = [];
        var winTop = $(window).scrollTop();
        var rangeTop = 200;
        var rangeBottom = 500;
        $('.navmenu').find('a').each(function() {
            contentTop.push($($(this).attr('href')).offset().top);
            contentBottom.push($($(this).attr('href')).offset().top + $($(this).attr('href')).height());
        })
        $.each(contentTop, function(i) {
            if (winTop > contentTop[i] - rangeTop && winTop < contentBottom[i] - rangeBottom) {
                $('.navmenu li')
                        .removeClass('active')
                        .eq(i).addClass('active');
            }
        })
    }
    ;

    $(document).ready(function() {
        calculateScroll();
        $(window).scroll(function(event) {
            calculateScroll();
        });
        $('.navmenu ul li a').click(function() {
            $('html, body').animate({scrollTop: $(this.hash).offset().top - 80}, 800);
            return false;
        });
    });
</script>	
<script type="text/javascript" charset="utf-8">

    jQuery(document).ready(function() {
        jQuery(".pretty a[rel^='prettyPhoto']").prettyPhoto({animation_speed: 'normal', theme: 'light_square', slideshow: 3000, autoplay_slideshow: true, social_tools: ''});

    });
</script>