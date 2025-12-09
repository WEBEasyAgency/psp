const ids = [154, 155, 156, 157, 158, 160, 161];

async function check() {
  for (const id of ids) {
    try {
      const r = await fetch(`https://psp.realeasystudio.site/backend/api/calc/${id}/params`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: '{}'
      });
      const data = await r.json();
      const count = (data.mat_select_params || []).length;

      if (count > 0) {
        console.log(`Calc${id}: ${count} mat_select_params - NEEDS FIX`);
        console.log(`  Variables: ${data.mat_select_params.map(m => m.variable).join(', ')}`);
      } else {
        console.log(`Calc${id}: ${count} mat_select_params - OK (no materials)`);
      }
    } catch(e) {
      console.log(`Calc${id}: Error - ${e.message}`);
    }
  }
}

check();
