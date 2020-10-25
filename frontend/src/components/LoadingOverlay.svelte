<script>
  import { Overlay, ProgressCircular } from 'svelte-materialify/src';

  export let show = false;
</script>

<Overlay bind:active={show}>
    <ProgressCircular indeterminate size={128} />
</Overlay>
