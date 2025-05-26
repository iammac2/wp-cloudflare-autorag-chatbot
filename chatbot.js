
/* global AutoRAGConfig */
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('chat-input');
  const chatWindow = document.getElementById('chat-window');

  const appendMessage = (author, text) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'bubble ' + (author === 'You' ? 'user' : 'bot');
    wrapper.textContent = text;
    chatWindow.appendChild(wrapper);
    chatWindow.scrollTop = chatWindow.scrollHeight;
  };

  input.addEventListener('keypress', async (e) => {
    if (e.key !== 'Enter') return;
    const userMessage = input.value.trim();
    if (!userMessage) return;

    appendMessage('You', userMessage);
    input.value = '';
    appendMessage('Bot', '…');

    try {
      const response = await fetch(AutoRAGConfig.endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: userMessage })
      });

      if (!response.ok) throw new Error('HTTP ' + response.status);

      const data = await response.json();
      chatWindow.lastChild.textContent =
        data?.result?.response || 'No answer';
    } catch (err) {
      chatWindow.lastChild.textContent = 'Error – ' + err.message;
    }
  });
});
