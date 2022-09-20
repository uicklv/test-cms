<script>
    wind = window.open("", "_blank");
    wind.document.write(`<pre><code><?= $this->json ?></code></pre>`);
    wind.document.title = '<?= $this->filename ?>';
    wind = null;
</script>
