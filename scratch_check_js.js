import fs from 'fs';
const content = fs.readFileSync('resources/views/hotel_admin/devices/index.blade.php', 'utf8');
const match = content.match(/<script>([\s\S]*?)<\/script>/);
let js = match[1];
js = js.replace(/\{\{.*?\}\}/g, '"dummy_blade_value"');
js = js.replace(/\{!!.*?!!\}/g, '"dummy_blade_raw"');

// Write the processed script to see what node actually reads
fs.writeFileSync('scratch_extracted_js.txt', js);
console.log('Written to scratch_extracted_js.txt, length:', js.length);
