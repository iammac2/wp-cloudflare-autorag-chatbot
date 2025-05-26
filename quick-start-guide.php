
    <div id="autorag-quick-start" style="max-width:800px;font-size:14px;line-height:1.6">
        <hr>
        <h2>Quick 15-Minute Cloudflare AutoRAG Setup Guide</h2>
        
        <p>Pre-requisite: Cloudflare account - free tier.</p>

        <ol>
            <li>
                <h3>Prepare your knowledge base</h3>
                <p><b>Dashboard → <em>R2 Object Storage</em> → <em>Create Bucket</em></b></p>
                <p>
                    • Upload your PDFs, text or Office docs to an
                    <strong>R2 bucket</strong>.<br>
                    • Or just drag one file into the AutoRAG wizard – it will create the bucket for
                    you.
                </p>
            </li>

            <li>
                <h3>Create an AutoRAG instance</h3>
                <p>
                    <b>Dashboard → <em>AI &raquo; AutoRAG</em> → <em>Create AutoRAG</em></b>.<br>
                    Pick the R2 bucket, accept the default embedding model &amp; LLM, then
                    click <strong>Create</strong>. Wait until the status changes from
                    “Ingesting” to “Ready”.
                </p>
            </li>

            <li>
                <h3>Generate an API token</h3>
                <p>
                    Open your AutoRAG new instance generate a new API token<b> <em>Use AutoRAG &raquo; API</em> → <strong>Create API Token</strong></b>.<br>
                       • <code>AutoRAG = Edit</code><br>
                       • <code>AI Gateway = Edit</code><br>
                       • <code>Vectorize = Edit</code><br>
                       • <code>Workers R2 Storage = Edit</code><br>
                       • <code>Workers Scripts = Edit</code><br>
                       • <code>Account Settings = Edit</code><br>
                    The wizard auto-fills two scopes: <code>AutoRAG – Read</code> and
                    <code>AutoRAG – Edit</code>. Keep them, create the token, then click the
                    clipboard icon 📋 to copy the full <em>eyJ…</em> string.
                </p>
            </li>

            <li>
                <h3>Verify with cURL</h3>
                <pre style="white-space:pre-wrap">
<code>curl -X POST \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer &lt;YOUR_TOKEN&gt;" \
  -d '{"query":"Ping"}' \
  https://api.cloudflare.com/client/v4/accounts/&lt;ACCOUNT_ID&gt;/autorag/rags/&lt;RAG_SLUG&gt;/ai-search</code>
                </pre>
                <p>
                    A successful call returns <code>"success": true</code>.  
                    Errors:
                    <code>10000 authentication error</code> = bad/short token;  
                    <code>7002 autorag_not_found</code> = wrong slug or account ID.
                </p>
            </li>

            <li>
                <h3>Fill the fields below</h3>
                <ul>
                    <li>Account ID (from the AutoRAG URL)</li>
                    <li>RAG slug (e.g. <code>fragrant-silence-ced5</code>)</li>
                    <li>API token (the long <em>eyJ…</em> string)</li>
                </ul>
            </li>

            <li>
                <h3>Save &amp; test the chatbot</h3>
                <p>
                    Reload any page that contains the shortcode
                    <code>[autorag_chatbot]</code>.  
                    Open DevTools → Network; the call to
                    <code>/wp-json/autorag/v1/search</code> should return 200 with a JSON
                    answer.
                </p>
            </li>

            <li>
                <h3>(Optional) keep your RAG fresh</h3>
                <p>
                    Upload new documents to the same R2 bucket – AutoRAG picks them up
                    automatically, or schedule a nightly <code>r2 sync</code>.
                </p>
            </li>
        </ol>
    </div>
