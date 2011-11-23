var tables = new Array();
var headerRowDivs = new Array();
var headerColumnDivs = new Array();
var bodyDivs = new Array();
var widths = new Array();
var heights = new Array();
var borderHorizontals = new Array();
var borderVerticals = new Array();
var tableWidths = new Array();
var tableHeights = new Array();
var arrayCount = 0;
var paddingTop = 0;
var paddingBottom = 0;
var paddingLeft = 0;
var paddingRight = 0;

function CleanUpArrays()
{
	tables = [];
	headerRowDivs = [];
	headerColumnDivs = [];
	bodyDivs = [];
	widths = [];
	heights = [];
	borderHorizontals = [];
	borderVerticals = [];
	tableWidths = [];
	tableHeights = [];
	arrayCount = 0;
	paddingTop = 0;
	paddingBottom = 0;
	paddingLeft = 0;
	paddingRight = 0;
}

function ScrollTableAbsoluteSize(table, width, height)
{
	CleanUpArrays();
	ScrollTable(table, null, null, width, height);
	//CleanUpArrays();
}

function ScrollTableRelativeSize(table, borderHorizontal, borderVertical)
{
	CleanUpArrays();
	ScrollTable(table, borderHorizontal, borderVertical, null, null);
	//CleanUpArrays();
}

function ScrollTable(table, borderHorizontal, borderVertical, width, height)
{
	var childElement = 0;
	if (table.childNodes[0].tagName == null)
	{
		childElement = 1;
	}
	
	var cornerDiv = table.childNodes[childElement].childNodes[0].childNodes[childElement].childNodes[childElement];
	var headerRowDiv = table.childNodes[childElement].childNodes[0].childNodes[(childElement + 1) * 2 - 1].childNodes[childElement];
	var headerColumnDiv = table.childNodes[childElement].childNodes[childElement + 1].childNodes[childElement].childNodes[childElement];
	var bodyDiv = table.childNodes[childElement].childNodes[childElement + 1].childNodes[(childElement + 1) * 2 - 1].childNodes[childElement];
	
	tables[arrayCount] = table;
	headerRowDivs[arrayCount] = headerRowDiv;
	headerColumnDivs[arrayCount] = headerColumnDiv;
	bodyDivs[arrayCount] = bodyDiv;
	borderHorizontals[arrayCount] = borderHorizontal;
	borderVerticals[arrayCount] = borderVertical;
	tableWidths[arrayCount] = width;
	tableHeights[arrayCount] = height;
	ResizeCells(table, cornerDiv, headerRowDiv, headerColumnDiv, bodyDiv);	
	
	widths[arrayCount] = bodyDiv.offsetWidth;
	heights[arrayCount] = bodyDiv.offsetHeight;
	arrayCount++;
	ResizeScrollArea();
	
	bodyDiv.onscroll = SyncScroll;
	if (borderHorizontal != null)
	{
		window.onresize = ResizeScrollArea;
	}
}

function ResizeScrollArea()
{
	var isIE = true;
	var scrollbarWidth = 17;
	if (!document.all)
	{
		isIE = false;
		scrollbarWidth = 19;
	}
	
	var k = 0;
	for (k = 0; k < arrayCount; k++)
	{
		bodyDivs[k].style.overflow = "scroll";
		bodyDivs[k].style.overflowX = "scroll";
		bodyDivs[k].style.overflowY = "scroll";
		var diffWidth = 0;
		var diffHeight = 0;
		var scrollX = true;
		var scrollY = true;
		
		var columnWidth = headerColumnDivs[k].offsetWidth;
		if (borderHorizontals[k] != null)
		{
			var width = document.documentElement.clientWidth - borderHorizontals[k] - columnWidth;
		}
		else
		{
			var width = tableWidths[k];
		}
		
		if (width > widths[k])
		{
			width = widths[k];
			bodyDivs[k].style.overflowX = "hidden";
			scrollX = false;
		}
		
		var columnHeight = headerRowDivs[k].offsetHeight;
		if (borderVerticals[k] != null)
		{
			var height = document.documentElement.clientHeight - borderVerticals[k] - columnHeight;
		}
		else
		{
			var height = tableHeights[k];
		}
		
		if (height > heights[k])
		{
			height = heights[k];
			bodyDivs[k].style.overflowY = "hidden";
			scrollY = false;
		}

		headerRowDivs[k].style.width = width + "px";
		headerRowDivs[k].style.overflow = "hidden";
		headerColumnDivs[k].style.height = height + "px";
		headerColumnDivs[k].style.overflow = "hidden";
		bodyDivs[k].style.width = width + scrollbarWidth + "px";
		bodyDivs[k].style.height = height + scrollbarWidth + "px";

		if (!scrollX && isIE)
		{
			bodyDivs[k].style.overflowX = "hidden";
			bodyDivs[k].style.height = bodyDivs[k].offsetHeight - scrollbarWidth + "px";
		}
		if (!scrollY && isIE)
		{
			bodyDivs[k].style.overflowY = "hidden";
			bodyDivs[k].style.width = bodyDivs[k].offsetWidth - scrollbarWidth + "px";
		}
		if (!scrollX && !scrollY && !isIE)
		{
			bodyDivs[k].style.overflow = "hidden";
		}
	}
}

function ResizeCells(table, cornerDiv, headerRowDiv, headerColumnDiv, bodyDiv)
{
	var childElement = 0;
	if (table.childNodes[0].tagName == null)
	{
		childElement = 1;
	}
	
	SetWidth(
		cornerDiv.childNodes[childElement].childNodes[childElement].childNodes[0].childNodes[childElement],
		headerColumnDiv.childNodes[childElement].childNodes[childElement].childNodes[0].childNodes[0]);
	
	SetHeight(
		cornerDiv.childNodes[childElement].childNodes[childElement].childNodes[0].childNodes[childElement],
		headerRowDiv.childNodes[childElement].childNodes[childElement].childNodes[0].childNodes[childElement]);
	
	var headerRowColumns = headerRowDiv.childNodes[childElement].childNodes[childElement].childNodes[0].childNodes;
	var bodyColumns = bodyDiv.childNodes[childElement].childNodes[childElement].childNodes[0].childNodes;
	for (i = 0; i < headerRowColumns.length; i++)
	{
		if (headerRowColumns[i].tagName == "TD" || headerRowColumns[i].tagName == "TH")
		{
			SetWidth(
				headerRowColumns[i], 
				bodyColumns[i], 
				i == headerRowColumns.length - 1);
		}
	}
	
	var headerColumnRows = headerColumnDiv.childNodes[childElement].childNodes[childElement].childNodes;
	var bodyRows = bodyDiv.childNodes[childElement].childNodes[childElement].childNodes;
	for (j = 0; j < headerColumnRows.length; j++)
	{
		if (headerColumnRows[j].tagName == "TR")
		{
			SetHeight(
				headerColumnRows[j].childNodes[0],
				bodyRows[j].childNodes[childElement],
				j == headerColumnRows.length - 1);
		}
	}
}

function SetWidth(element1, element2, isLastColumn)
{
	var diff = paddingLeft + paddingRight;
	
	if (element1.offsetWidth < element2.offsetWidth)
	{
		element1.childNodes[0].style.width = element2.offsetWidth - diff + "px";
		element2.childNodes[0].style.width = element2.offsetWidth - diff + "px";
	}
	else
	{
		element2.childNodes[0].style.width = element1.offsetWidth - diff + "px";
		element1.childNodes[0].style.width = element1.offsetWidth - diff + "px";
	}
}

function SetHeight(element1, element2, isLastRow)
{
	var diff = paddingTop + paddingBottom;
	
	if (element1.offsetHeight < element2.offsetHeight)
	{
		element1.childNodes[0].style.height = element2.offsetHeight - diff + "px";
		element2.childNodes[0].style.height = element2.offsetHeight - diff + "px";
	}
	else
	{
		element2.childNodes[0].style.height = element1.offsetHeight - diff + "px";
		element1.childNodes[0].style.height = element1.offsetHeight - diff + "px";
	}
}

function SyncScroll()
{
	for (l = 0; l < arrayCount; l++)
	{
		headerRowDivs[l].scrollLeft = bodyDivs[l].scrollLeft;
		headerColumnDivs[l].scrollTop = bodyDivs[l].scrollTop;
	}
}