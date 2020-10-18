<script>
  import {AppBar, Button, Icon} from 'svelte-materialify/src';
  import {mdiMenu} from '@mdi/js';
</script>

<AppBar class="light-green darken-2">
    <div slot="icon">
        <Button fab depressed style="background-color: transparent">
            <Icon path={mdiMenu} />
        </Button>
    </div>
    <span slot="title">Jugendtreff Registrierung</span>
</AppBar>

<style>
    .AppBar {

    }
</style>
