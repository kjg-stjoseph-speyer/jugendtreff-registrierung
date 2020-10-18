<script>
  import { Footer } from 'svelte-materialify/src';
</script>

<Footer class="justify-left pa-2 green" absolute>
    <span class="version">v1.0.0-preview</span>
</Footer>

<style>
    .Footer {
        position: fixed;
        left: 0;
        bottom: 0;
    }

    .version {
        font-size: 10pt;
        font-style: italic;
    }
</style>
