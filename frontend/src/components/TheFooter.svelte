<script>
  import { Footer, Icon } from 'svelte-materialify/src';
  import {mdiGithub} from '@mdi/js';
</script>

<Footer class="justify-left pa-2 light-green darken-2" absolute>
    <div style="width: 100%">
        <span class="version float-left">v1.0.0-preview</span>
        <a class="float-right" on:click={() => window.location.href = "https://github.com/kjg-stjoseph-speyer/jugendtreff-registrierung"}>
            <Icon class="black-text" path={mdiGithub}/>
        </a>
    </div>
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
