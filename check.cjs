const fs = require('fs');
const html = fs.readFileSync('create-dump.html', 'utf8');

const start = html.lastIndexOf('x-data="{');
// Find the closing of the x-data attribute: the sequence `}"` immediately before `\n    >`
const end = html.indexOf('}"\n    >', start);
const expr = html.slice(start + 'x-data="'.length, end + 1);

try {
  new Function('return ' + expr);
  console.log('x-data JS: OK');
} catch (e) {
  console.log('x-data JS ERROR: ' + e.message);
}

for (const s of [
  '1. Find a student',
  '2. Choose subjects',
  '3. Enrollment details',
  '4. Review &amp; confirm',
  'Next: Choose subjects',
  'Next: Enrollment details',
  'Next: Review',
  'Enroll in Selected Subjects',
  'Year 1',
  'Year 5',
  '1st Semester',
  'Summer Semester',
  'type="submit"',
  'Enrollment steps',
]) {
  console.log((html.includes(s) ? 'FOUND  ' : 'MISSING') + '  ' + s);
}
