/* Global AutoRAGConfig */
document.addEventListener('DOMContentLoaded', () => {
  const input      = document.getElementById('chat-input');
  const chatWindow = document.getElementById('chat-window');
  const sendBtn    = document.querySelector('#autorag-chatbot .send-btn');

  const appendMessage = (author, textOrHTML, isHTML = false) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'bubble ' + (author === 'You' ? 'user' : 'bot');
    if (isHTML) {
       wrapper.innerHTML = textOrHTML;
    } else {
       wrapper.textContent = textOrHTML;
    }
    chatWindow.appendChild(wrapper);
    chatWindow.scrollTop = chatWindow.scrollHeight;
    return wrapper;
  };

  async function handleSend () {
    const userMessage = input.value.trim();
    if (!userMessage) return;

    appendMessage('You', userMessage);
    input.value = '';

    const bubble = appendMessage(
      'Bot',
      '<span class="typing-dots"><span></span><span></span><span></span></span>',
      true
    );

    try {
      const response = await fetch(AutoRAGConfig.endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: userMessage })
      });

      const text = await response.text();

      let data;
      try {
        data = JSON.parse(text);
      } catch (parseErr) {
        throw new Error('Server returned non-JSON – status ' + response.status);
      }

      if (!response.ok) {
        const msg =
              data.error?.errors?.[0]?.message
           || data.errors?.[0]?.message
           || 'HTTP ' + response.status;
        throw new Error(msg);
      }

      bubble.textContent = data.result?.response || 'No answer';

    } catch (err) {
      bubble.textContent =
        err.message.startsWith('Workers AI:')
          ? 'Sorry I had a problem, please re-phrase the question.'
          : 'Error – ' + err.message;
    }
  }

  /* wire up both triggers */
  input.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') handleSend();
  });

  if (sendBtn) {
    sendBtn.addEventListener('click', handleSend);
  }
});
