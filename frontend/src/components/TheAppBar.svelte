<script>
  import {AppBar, Button, Icon} from 'svelte-materialify/src';
</script>

<AppBar class="green">
    <div slot="icon">
        <Button fab depressed>
            <Icon class="mdi mdi-menu" />
        </Button>
    </div>
    <span slot="title">Jugendtreff Registrierung</span>
</AppBar>

<style>
    .AppBar {

    }
</style>
