<?php
?>
    <script>AOS.init()</script>
</body>

<footer class="page-footer font-small blue-grey lighten-5 border-top">
    <div class="container pt-1 mt-3">
        <div class="row mt-3 dark-grey-text flex-row-reverse">
            <div class="col-md-4 col-12">
                <div class="footer-little-title">
                    <h6><a href="../../contact-us.php">צור קשר</a></h6>
                    <hr class="teal accent-3 mb-4 mt-0 d-inline-block" style="width: 60px;">
                </div>

                <div class="d-flex justify-content-center text-left">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">050-333-0412<i class="fas fa-home mr-3"></i> </li>
                        <li class="list-group-item"><a href="mailto:<?php echo \BetterLife\System\SystemConstant::SYSTEM_EMAIL ?>"><?php echo \BetterLife\System\SystemConstant::SYSTEM_EMAIL ?></a><i class="fas fa-envelope mr-3"></i></li>
                        <li class="list-group-item">Kfar - Warburg, Hadarom<i class="fas fa-phone mr-3"></i></li>
                    </ul>

                </div>

            </div>

            <div class="col-md-4 col-12">
                <div class="text-center">
                    <h6>עקבו אחרינו</h6>
                    <hr class="teal accent-3 mb-4 mt-0 d-inline-block" style="width: 80px;">
                </div>

                <div>
                    <ul class="list-inline text-center footer-icon">
                        <li class="list-inline-item"><a href="#" class="fab fa-facebook fa-2x" style="color:#45619D"></a></li>
                        <li class="list-inline-item"><a href="#" class="fab fa-instagram fa-2x" style="color:#D44C3D"></a></li>
                        <li class="list-inline-item"><a href="#" class="fab fa-twitter-square fa-2x" style="color:#7CBBEB"></a></li>
                        <li class="list-inline-item"><a href="#" class="fab fa-linkedin fa-2x" style="color:#1883BB"></a></li>
                    </ul>

                </div>

            </div>

            <div class="col-md-4 col-12" style="direction: ltr">
                <div class="footer-little-title-right">
                    <h6 class="hover-fade" onclick="window.scrollTo(0, 0);">חזור למעלה</h6>
                    <hr class="teal accent-3 mb-4 mt-0 d-inline-block" style="width: 80px;">
                </div>

                <div class="d-flex justify-content-center text-right">
                    <ul class="list-group list-group-flush footer-right-li">
                        <li class="list-group-item hover-fade"><a href="../../articles/articles.php">לכל הכתבות </a><i class="fas fa-home ml-3"></i></li>
                        <li class="list-group-item hover-fade"><a href="../../doctors.php">צוות הרופאים </a><i class="fas fa-envelope ml-3"></i></li>
                        <li class="list-group-item hover-fade"><a href="../../about-us.php">אודות </a><i class="fas fa-phone ml-3"></i></li>
                    </ul>

                </div>

            </div>


        </div>
    </div>
    <div class="footer-copyright text-center text-black-50 py-3">© BetterLife <?php echo date("Y")?> ©</div>
</footer>
</html>

