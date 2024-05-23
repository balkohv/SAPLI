chrome.action.setBadgeText(
  {
    text: "off"
  }
);
chrome.action.setBadgeBackgroundColor(
  {
    color: "#FF0000"
  }
);
chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
  if (message.type === "updateBadge" && sender.tab) {
    // Limiter la longueur du texte pour le badge (facultatif)
    const badgeText = message.text;

    // Mettre à jour le texte du badge pour l'onglet actuel
    chrome.action.setBadgeText({
      text: badgeText,
      tabId: sender.tab.id
    });
    chrome.action.setBadgeBackgroundColor(
      {
        color: message.color,
        tabId: sender.tab.id
      }
    );

    sendResponse({ message: "Badge updated" });
  }
  return true; // Indique que la réponse sera envoyée de manière asynchrone
});