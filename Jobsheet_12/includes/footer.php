</main>

    <!-- [Jobsheet 01] Footer Halaman -->
    <footer>
        <p>&copy; 2026 SIMPUS-Mini &mdash; Jobsheet 12</p>
    </footer>
    <!-- [Jobsheet 05] Panggilan Script JavaScript Utama -->
    <script src="<?php echo $base; ?>assets/js/app.js"></script>
    <?php if (!empty($extra_scripts)): foreach ($extra_scripts as $src): ?>
    <script src="<?php echo $src; ?>"></script>
    <?php endforeach;
    endif; ?>
</body>
</html>