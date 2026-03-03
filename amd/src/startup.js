import {exception as displayException} from "core/notification";
import Templates from "core/templates";

const context = {
  login: "",
  logout: "",
  autograded: true,
};

const Selectors = {
  elements: {
    tempPlaceholder: '[data-element="mod_jupyter/placeholder"]',
    notebookFrame: "#iframe",
  },
};

const withCacheBuster = (url) => {
  const parsed = new URL(url, window.location.href);
  parsed.searchParams.set("_", Date.now().toString());
  return parsed.toString();
};

const loadFrame = (frame, url, timeout = 4000) => new Promise((resolve) => {
  const cleanup = () => {
    frame.removeEventListener("load", finish);
    frame.removeEventListener("error", finish);
    window.clearTimeout(timer);
  };

  const finish = () => {
    cleanup();
    resolve();
  };

  const timer = window.setTimeout(finish, timeout);
  frame.addEventListener("load", finish, {once: true});
  frame.addEventListener("error", finish, {once: true});
  frame.src = withCacheBuster(url);
});

const syncJupyterSession = async(login, logout) => {
  const notebookFrame = document.querySelector(Selectors.elements.notebookFrame);
  if (!notebookFrame) {
    return;
  }

  notebookFrame.src = "about:blank";

  if (logout) {
    const logoutFrame = document.createElement("iframe");
    logoutFrame.setAttribute("aria-hidden", "true");
    logoutFrame.style.display = "none";
    document.body.appendChild(logoutFrame);
    await loadFrame(logoutFrame, logout, 3000);
    logoutFrame.remove();
  }

  notebookFrame.src = withCacheBuster(login);
};

export const init = ({login, logout, autograded}) => {
  context.login = login;
  context.logout = logout;
  context.autograded = autograded == 1;
  Templates.renderForPromise("mod_jupyter/manage", context)
    .then(({html, js}) => {
      Templates.replaceNodeContents(
        Selectors.elements.tempPlaceholder,
        html,
        js
      );
      return syncJupyterSession(login, logout);
    })
    .catch((error) => displayException(error));
};
