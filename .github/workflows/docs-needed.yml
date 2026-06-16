import fs from 'node:fs';
import path from 'node:path';

const repoRoot = process.cwd();
const configPath = path.join(repoRoot, 'docs-sync.config.json');
const config = JSON.parse(fs.readFileSync(configPath, 'utf8'));

const issueTitle = process.env.ISSUE_TITLE || '';
const issueBody = process.env.ISSUE_BODY || '';
const issueUrl = process.env.ISSUE_URL || '';
const issueNumber = process.env.ISSUE_NUMBER || '';

function pick(marker, fallback = '') {
  const re = new RegExp(`<!--\\s*${marker}\\s*:\\s*([^>]+?)\\s*-->`, 'i');
  const match = issueBody.match(re);
  return (match?.[1] || fallback).trim();
}

function inferTemplateKey() {
  const explicit = pick('docs-template', '');
  if (explicit) return explicit;
  const text = `${issueTitle} ${issueBody}`.toLowerCase();
  if (text.includes('install') || text.includes('setup')) return 'installation';
  if (text.includes('getting started') || text.includes('syntax') || text.includes('footnote')) return 'getting-started';
  if (text.includes('pro')) return 'pro-installation';
  return config.defaultTemplate;
}

function inferTargetDoc(templateKey) {
  const explicit = pick('docs-target', '');
  if (explicit) return explicit;
  return config.targets[templateKey] ?? config.targets[config.defaultTemplate];
}

function renderTemplate(template, vars) {
  return template.replace(/{{\s*([A-Z_]+)\s*}}/g, (_, key) => vars[key] ?? '');
}

function updateFile(filePath, renderedBlock) {
  const abs = path.join(repoRoot, filePath);
  const original = fs.readFileSync(abs, 'utf8');
  const begin = '<!-- docs-bot:start -->';
  const end = '<!-- docs-bot:end -->';
  const block = `${begin}\n${renderedBlock}\n${end}`;
  let updated;

  if (original.includes(begin) && original.includes(end)) {
    updated = original.replace(new RegExp(`${begin}[\\s\\S]*?${end}`), block);
  } else if (/^#\s+/m.test(original)) {
    const match = original.match(/^(#\s+.*?\n)/m);
    const insertAt = match ? match.index + match[0].length : 0;
    updated = `${original.slice(0, insertAt)}\n${block}\n${original.slice(insertAt)}`.trimEnd() + '\n';
  } else {
    updated = `${original.trimEnd()}\n\n${block}\n`;
  }

  fs.mkdirSync(path.dirname(abs), { recursive: true });
  fs.writeFileSync(abs, updated);
}

const templateKey = inferTemplateKey();
const targetDoc = inferTargetDoc(templateKey);
const templateFile = path.join(repoRoot, config.templates[templateKey] ?? config.templates[config.defaultTemplate]);
const template = fs.readFileSync(templateFile, 'utf8');

const summary = pick('change-summary', issueTitle) || issueTitle;
const rendered = renderTemplate(template, {
  ISSUE_TITLE: issueTitle,
  ISSUE_BODY: issueBody,
  ISSUE_URL: issueUrl,
  ISSUE_NUMBER: issueNumber,
  SUMMARY: summary,
  TEMPLATE_KEY: templateKey,
  TARGET_DOC: targetDoc,
});

updateFile(targetDoc, rendered);

process.stdout.write(JSON.stringify({ templateKey, targetDoc, summary }, null, 2));

const out = process.env.GITHUB_OUTPUT;
if (out) {
  fs.appendFileSync(out, `pr_title=docs: ${summary}\n`);
  fs.appendFileSync(out, `pr_body<<EOF\n${[
    'Automated docs update from GitHub issue.',
    `- Issue: ${issueUrl}`,
    `- Template: ${templateKey}`,
    `- Target: ${targetDoc}`,
    '',
    summary,
  ].join('\n')}\nEOF\n`);
  fs.appendFileSync(out, `summary=${summary}\n`);
}
