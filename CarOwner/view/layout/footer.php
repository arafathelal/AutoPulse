</div><!-- END .container -->

<footer style="text-align:center;padding:2rem;color:#95a5a6;font-size:0.8rem;border-top:1px solid #eee;margin-top:auto;">
    &copy; <?= date('Y') ?> AutoCare
</footer>

<script>
const menuToggle=document.getElementById('mobile-menu');
const navList=document.getElementById('nav-list');
menuToggle.addEventListener('click',()=>navList.classList.toggle('active'));
document.querySelectorAll('.nav-links a').forEach(a=>{
    a.addEventListener('click',()=>navList.classList.remove('active'));
});
</script>

</body>
</html>
