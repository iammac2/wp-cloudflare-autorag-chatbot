
=== WP Cloudflare AutoRAG Chatbot ===
Contributors: neuno
Tags: ai, chatbot, cloudflare, autorag
Requires at least: 6.0
Tested up to: 6.5
Stable tag: 1.3
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A shortcode that embeds a chatbot powered by Cloudflare AutoRAG. All requests are proxied through WordPress so your API token never touches the browser.

== Description ==
* Ask natural‑language questions about your private docs (PDF, Word, Markdown, etc.).  
* Documents live in Cloudflare R2 and are indexed by AutoRAG; no extra servers to maintain.  
* Server‑side proxy eliminates CORS problems and hides your API token.

Insert the shortcode `[autorag_chatbot]` wherever you want the chat UI to appear.

== Installation ==
1. Upload the plugin ZIP via **Plugins → Add New → Upload**.
2. Activate **WP Cloudflare AutoRAG Chatbot**.
3. Go to **Settings → AutoRAG Chatbot**, paste your **Account ID**, **RAG slug** and **API token**.
4. Add `[autorag_chatbot]` to any post, page or template.

== Frequently Asked Questions ==
= Does this store chat or PII? =
No. Each question is proxied to Cloudflare’s API and discarded; nothing is saved by the plugin.

= My console shows "autorag_not_found" =
Double‑check the RAG slug and that your token belongs to the same account.

== Screenshots ==
1. Front‑end chatbot.
2. Settings screen with quick‑start guide.

== Changelog ==
= 1.3 =
* First public release on WordPress.org – localisation, sanitisation and readme added.

== Upgrade Notice ==
1.3 – First release on WordPress.org – please overwrite the entire folder.
