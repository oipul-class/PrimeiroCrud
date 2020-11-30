'use strict';

const projetos = [
    {"nome":"IMC" , "link":"IMC/index.html"},
    {"nome":"SlideShow" , "link":"SlideShow/index.html"},
    {"nome":"Clone" , "link":"Clone/index.html"},
    {"nome":"Drumkit" , "link":"DrumKit/index.html"},
    {"nome":"Trabalho API" , "link":"TrabalhoAPI1"}
];


const container = document.getElementById("indexContainer");

const createDiv = ( name, link) => {
    const div = document.createElement("div");
    div.classList.add("project");
    div.innerHTML = `<a href="${link}">
                        ${name}
                    </a>`;
    return div;
};


projetos.forEach(element => {
    container.appendChild(createDiv(element.nome , element.link));
});