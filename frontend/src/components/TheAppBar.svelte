<script>
  import {AppBar, Button, Icon} from 'svelte-materialify/src';
  import {mdiMenu} from '@mdi/js';
  import { createEventDispatcher, afterUpdate } from 'svelte'
  import {readCookie} from "../CookieHelper";

  const dispatch = createEventDispatcher();

  let admin = false;
  afterUpdate(async () => {
    admin = readCookie("admin") !== "";
  });
</script>

<AppBar class="light-green darken-2">
    <div slot="icon">
        <Button fab depressed style="background-color: transparent" on:click={() => dispatch("showdrawer")}>
            <Icon path={mdiMenu} />
        </Button>
    </div>
    <span slot="title">Jugendtreff Registrierung {admin ? "(Admin)" : ""}</span>
</AppBar>

<style>
    .AppBar {

    }
</style>
