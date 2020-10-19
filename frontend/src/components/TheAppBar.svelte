<script>
  import {AppBar, Button, Icon} from 'svelte-materialify/src';
  import {mdiMenu} from '@mdi/js';
  import { createEventDispatcher } from 'svelte'

  const dispatch = createEventDispatcher();
</script>

<AppBar class="light-green darken-2">
    <div slot="icon">
        <Button fab depressed style="background-color: transparent" on:click={() => dispatch("showdrawer")}>
            <Icon path={mdiMenu} />
        </Button>
    </div>
    <span slot="title">Jugendtreff Registrierung</span>
</AppBar>

<style>
    .AppBar {

    }
</style>
